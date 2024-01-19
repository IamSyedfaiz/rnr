@extends('email.layout.app')
@section('content')
    <td class="wrapper">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    @foreach ($body as $replacedTemplate)
                        {!! $replacedTemplate !!}
                    @endforeach
                    <h3>RNR.</h3>
                    <h3>Good luck! Hope it works.</h3>
                </td>
            </tr>
        </table>
    </td>
@endsection
