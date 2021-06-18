<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/dist/css/skins/_all-skins.min.css') }}">
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-confirm.min.css') }}"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('lte/plugins/iCheck/square/blue.css') }}">

    <script type="text/javascript" href="{{ asset('lte/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" href="{{ asset('lte/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" href="{{ asset('lte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" href="{{ asset('lte/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script type="text/javascript" href="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
</head>
<body class="hold-transition" background="{{ url('images/bg.png') }}" style="background-size: 100%;">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">

                        <form method="POST" action="{{ route('register') }}" class="form-horizontal">
                            @csrf

                            <div>
                                <x-jet-label for="nama" value="{{ __('Nama') }}" />
                                <x-jet-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required autofocus autocomplete="nama" />
                            </div>

                            <div class="mt-4">
                                <x-jet-label for="alamat" value="{{ __('Alamat') }}" />
                                <x-jet-input id="alamat" class="block mt-1 w-full" type="text" name="alamat" :value="old('alamat')" required autofocus autocomplete="alamat" />
                            </div>

                            <div class="mt-4">
                                <x-jet-label for="npwp" value="{{ __('NPWP') }}" />
                                <x-jet-input id="npwp" class="block mt-1 w-full" type="number" name="npwp" :value="old('npwp')" required autofocus autocomplete="npwp" />
                            </div>

                            <div class="mt-4">
                                <x-jet-label for="no_hp" value="{{ __('Nomor HP') }}" />
                                <x-jet-input id="no_hp" class="block mt-1 w-full" type="number" name="no_hp" :value="old('no_hp')" required autofocus autocomplete="no_hp" />
                            </div>

                            <div class="mt-4">
                                <x-jet-label for="email" value="{{ __('Email') }}" />
                                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            </div>

                            <div class="mt-4">
                                <x-jet-label for="password" value="{{ __('Password') }}" />
                                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            </div>

                            <div class="mt-4">
                                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            </div>

                            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                <div class="mt-4">
                                    <x-jet-label for="terms">
                                        <div class="flex items-center">
                                            <x-jet-checkbox name="terms" id="terms"/>

                                            <div class="ml-2">
                                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </x-jet-label>
                                </div>
                            @endif

                            <div class="flex items-center justify-end mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                                    {{ __('Already registered?') }}
                                </a>

                                <x-jet-button class="ml-4">
                                    {{ __('Register') }}
                                </x-jet-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        console.log(response.message);
    </script>
</body>
</html>
