Thank you for choosing to subscribe to the email list: {{ $signup->maillist->display_name }}.

To confirm your subscription you'll need to follow the below link then click "Confirm" on that page.
{{ route('signup.confirm.show', ['signupKey' => $signup->key]) }}

If you did not request to subscribe to this list you can ignore this email.
