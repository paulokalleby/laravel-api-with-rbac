<x-mail::message>
# Redefinição de Senha

Olá, você solicitou a redefinição da sua senha.  
Use o código abaixo para continuar:

<x-mail::panel>
<span style="font-size: 28px; font-weight: bold; letter-spacing: 4px; display: block; text-align: center;">
    {{ $code }}
</span>
</x-mail::panel>

Esse código expira em **15 minutos**.  

Se você não solicitou, ignore este e-mail.

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
