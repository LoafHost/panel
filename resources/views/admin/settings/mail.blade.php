@extends('layouts.modern')

@section('title', 'Mail Settings')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold text-white mb-6">Mail Settings</h1>
    <div class="bg-gray-900 rounded-lg shadow-lg p-6">
        @if($disabled)
            <div class="bg-blue-800 text-white p-4 rounded-lg">
                This interface is limited to instances using SMTP as the mail driver. Please either use <code>php artisan p:environment:mail</code> command to update your email settings, or set <code>MAIL_DRIVER=smtp</code> in your environment file.
            </div>
        @else
            <form id="mail-settings-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- SMTP Host -->
                    <div>
                        <label for="smtp_host" class="block text-sm font-medium text-gray-300">SMTP Host</label>
                        <input type="text" id="smtp_host" name="mail:mailers:smtp:host" value="{{ old('mail:mailers:smtp:host', config('mail.mailers.smtp.host')) }}" required class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-2 text-sm text-gray-500">Enter the SMTP server address that mail should be sent through.</p>
                    </div>

                    <!-- SMTP Port -->
                    <div>
                        <label for="smtp_port" class="block text-sm font-medium text-gray-300">SMTP Port</label>
                        <input type="number" id="smtp_port" name="mail:mailers:smtp:port" value="{{ old('mail:mailers:smtp:port', config('mail.mailers.smtp.port')) }}" required class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-2 text-sm text-gray-500">Enter the SMTP server port that mail should be sent through.</p>
                    </div>

                    <!-- Encryption -->
                    <div class="md:col-span-2">
                        <label for="encryption" class="block text-sm font-medium text-gray-300">Encryption</label>
                        @php
                            $encryption = old('mail:mailers:smtp:encryption', config('mail.mailers.smtp.encryption'));
                        @endphp
                        <select id="encryption" name="mail:mailers:smtp:encryption" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="" @if($encryption === '') selected @endif>None</option>
                            <option value="tls" @if($encryption === 'tls') selected @endif>Transport Layer Security (TLS)</option>
                            <option value="ssl" @if($encryption === 'ssl') selected @endif>Secure Sockets Layer (SSL)</option>
                        </select>
                        <p class="mt-2 text-sm text-gray-500">Select the type of encryption to use when sending mail.</p>
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300">Username <span class="text-gray-500">(Optional)</span></label>
                        <input type="text" id="username" name="mail:mailers:smtp:username" value="{{ old('mail:mailers:smtp:username', config('mail.mailers.smtp.username')) }}" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-2 text-sm text-gray-500">The username to use when connecting to the SMTP server.</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">Password <span class="text-gray-500">(Optional)</span></label>
                        <input type="password" id="password" name="mail:mailers:smtp:password" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-2 text-sm text-gray-500">The password to use in conjunction with the SMTP username. Leave blank to continue using the existing password. To set the password to an empty value enter <code>!e</code> into the field.</p>
                    </div>

                    <hr class="md:col-span-2 border-gray-700">

                    <!-- Mail From -->
                    <div>
                        <label for="mail_from" class="block text-sm font-medium text-gray-300">Mail From</label>
                        <input type="email" id="mail_from" name="mail:from:address" value="{{ old('mail:from:address', config('mail.from.address')) }}" required class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-2 text-sm text-gray-500">Enter an email address that all outgoing emails will originate from.</p>
                    </div>

                    <!-- Mail From Name -->
                    <div>
                        <label for="mail_from_name" class="block text-sm font-medium text-gray-300">Mail From Name <span class="text-gray-500">(Optional)</span></label>
                        <input type="text" id="mail_from_name" name="mail:from:name" value="{{ old('mail:from:name', config('mail.from.name')) }}" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-2 text-sm text-gray-500">The name that emails should appear to come from.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    {!! csrf_field() !!}
                    <button type="button" id="testButton" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                        Test
                    </button>
                    <button type="button" id="saveButton" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                        Save
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent

    <script>
        function saveSettings() {
            return $.ajax({
                method: 'PATCH',
                url: '/admin/settings/mail',
                contentType: 'application/json',
                data: JSON.stringify({
                    'mail:mailers:smtp:host': $('input[name="mail:mailers:smtp:host"]').val(),
                    'mail:mailers:smtp:port': $('input[name="mail:mailers:smtp:port"]').val(),
                    'mail:mailers:smtp:encryption': $('select[name="mail:mailers:smtp:encryption"]').val(),
                    'mail:mailers:smtp:username': $('input[name="mail:mailers:smtp:username"]').val(),
                    'mail:mailers:smtp:password': $('input[name="mail:mailers:smtp:password"]').val(),
                    'mail:from:address': $('input[name="mail:from:address"]').val(),
                    'mail:from:name': $('input[name="mail:from:name"]').val()
                }),
                headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
            }).fail(function (jqXHR) {
                showErrorDialog(jqXHR, 'save');
            });
        }

        function testSettings() {
            swal({
                type: 'info',
                title: 'Test Mail Settings',
                text: 'Click "Test" to begin the test.',
                showCancelButton: true,
                confirmButtonText: 'Test',
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                customClass: {
                    popup: 'bg-gray-800',
                    title: 'text-white',
                    content: 'text-gray-300',
                    confirmButton: 'bg-indigo-600 hover:bg-indigo-700',
                    cancelButton: 'bg-gray-600 hover:bg-gray-700'
                }
            }, function () {
                $.ajax({
                    method: 'POST',
                    url: '/admin/settings/mail/test',
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                }).fail(function (jqXHR) {
                    showErrorDialog(jqXHR, 'test');
                }).done(function () {
                    swal({
                        title: 'Success',
                        text: 'The test message was sent successfully.',
                        type: 'success',
                        customClass: {
                            popup: 'bg-gray-800',
                            title: 'text-white',
                            content: 'text-gray-300',
                            confirmButton: 'bg-indigo-600 hover:bg-indigo-700'
                        }
                    });
                });
            });
        }

        function saveAndTestSettings() {
            saveSettings().done(testSettings);
        }

        function showErrorDialog(jqXHR, verb) {
            console.error(jqXHR);
            var errorText = '';
            if (!jqXHR.responseJSON) {
                errorText = jqXHR.responseText;
            } else if (jqXHR.responseJSON.error) {
                errorText = jqXHR.responseJSON.error;
            } else if (jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function (i, v) {
                    if (v.detail) {
                        errorText += v.detail + ' ';
                    }
                });
            }

            swal({
                title: 'Whoops!',
                text: 'An error occurred while attempting to ' + verb + ' mail settings: ' + errorText,
                type: 'error',
                customClass: {
                    popup: 'bg-gray-800',
                    title: 'text-white',
                    content: 'text-gray-300',
                    confirmButton: 'bg-red-600 hover:bg-red-700'
                }
            });
        }

        $(document).ready(function () {
            $('#testButton').on('click', saveAndTestSettings);
            $('#saveButton').on('click', function () {
                saveSettings().done(function () {
                    swal({
                        title: 'Success',
                        text: 'Mail settings have been updated successfully and the queue worker was restarted to apply these changes.',
                        type: 'success',
                        customClass: {
                            popup: 'bg-gray-800',
                            title: 'text-white',
                            content: 'text-gray-300',
                            confirmButton: 'bg-indigo-600 hover:bg-indigo-700'
                        }
                    });
                });
            });
        });
    </script>
@endsection
