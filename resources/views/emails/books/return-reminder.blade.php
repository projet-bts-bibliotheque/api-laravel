@component('mail::message')
# Rappel : Livre à retourner

Bonjour {{ $reservation->user->first_name }},

Nous vous rappelons que le livre "{{ $reservation->book->title }}" que vous avez emprunté 
le {{ $reservation->start->format('d/m/Y') }} doit être retourné.

Cela fait maintenant plus d'un mois que vous avez ce livre en votre possession.
Merci de le retourner dès que possible.

@component('mail::button', ['url' => $url])
Accéder à votre profil
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent