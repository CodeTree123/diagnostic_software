<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\GoogleAuthenticator;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\Shop;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function shopSetting()
    {
        $pageTitle = "Shop Setting";
        $shop = Shop::select('name', 'address','logo')->where('user_id', auth()->user()->id)->first();
        return view('user.shop.setting', compact('pageTitle', 'shop'));
    }
    public function createShopData(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than :max characters.',
            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than :max characters.',
            'logo.max' => 'The file may not be greater than :max kilobytes in size.',
            'logo.image' => 'The file must be an image.',
            'logo.mimes' => 'The file must be a JPEG, PNG, JPG, or GIF.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $ifExist = Shop::where('user_id', auth()->user()->id)->first();

        if (!$ifExist) {
            $shop = new Shop();
            $shop->user_id = auth()->user()->id;
            $shop->name = $request->name;
            $shop->address = $request->address;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $filePath = 'logos/' . $fileName;
                $file->move(public_path('logos'), $fileName);
                $shop->logo = $filePath;
            }
            $shop->save();
            $notify[] = ['success', 'Data added successfully'];
            return back()->withNotify($notify);
        } else {
            $shop = Shop::findOrFail($ifExist->id);
            $shop->name = $request->name;
            $shop->address = $request->address;

            if ($request->hasFile('logo')) {
                if ($shop->logo) {
                    unlink(public_path($shop->logo));
                }

                $file = $request->file('logo');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $filePath = 'logos/' . $fileName;
                $file->move(public_path('logos'), $fileName);
                $shop->logo = $filePath;
            }
            $shop->save();
            $notify[] = ['success', 'Data updated successfully'];
            return back()->withNotify($notify);
        }
    }
    public function home()
    {
        $pageTitle = "User Home";
        return view('user.dashboard', compact('pageTitle'));
    }

    public function newDashboard()
    {
        $pageTitle = "Diagnostics Center";
        $shop = Shop::select('name', 'address','logo')->where('user_id', auth()->user()->id)->first();
        return view('user.new.dashboard', compact('pageTitle', 'shop'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $pageTitle = "User Data";
        return view('user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state' => @$request->state,
            'zip' => $request->zip,
            'city' => $request->city,
        ];
        $user->profile_complete = 1;
        $user->save();
        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function show2faForm()
    {
        $general = gs();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view('user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }


    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view('user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view('user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }
}
