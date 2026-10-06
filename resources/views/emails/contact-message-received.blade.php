@php
	$m = $contactMessage;
	$brand = config('app.name', 'MDM');
@endphp
<div style="background:#f4f6fb; padding:32px 16px; font-family:Arial, Helvetica, sans-serif; color:#0b1324;">
	<div style="max-width:560px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden;">
		<div style="background:#050913; padding:28px 32px;">
			<p style="margin:0; color:#5ee7ff; font-size:12px; letter-spacing:3px; text-transform:uppercase; font-weight:bold;">{{ $brand }} Derma</p>
			<h1 style="margin:8px 0 0 0; color:#ffffff; font-size:24px;">Thank you, {{ $m->name }}.</h1>
		</div>
		<div style="padding:28px 32px; font-size:15px; line-height:1.6;">
			<p style="margin:0 0 16px 0;">We’ve received your message and our team will get back to you as soon as possible.</p>
			<p style="margin:0 0 8px 0; color:#64748b; font-size:13px;">Your message:</p>
			<div style="margin:0 0 24px 0; padding:16px; background:#f4f6fb; border-radius:12px; white-space:pre-wrap;">{{ $m->message }}</div>
			<a href="{{ url('/products') }}" style="display:inline-block; padding:12px 22px; border-radius:999px; background:#3277d8; color:#ffffff; text-decoration:none; font-weight:bold;">Browse our products</a>
		</div>
		<div style="padding:16px 32px; border-top:1px solid #e5e9f2; color:#94a3b8; font-size:12px;">
			© {{ date('Y') }} {{ config('app.copyright_holder') }} · This is an automatic confirmation, there is no need to reply.
		</div>
	</div>
</div>
