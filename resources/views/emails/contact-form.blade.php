<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #2563eb;">
        <h2 style="color: #1f2937; margin-top: 0;">New Message from OSCA Contact Form</h2>
        
        <p style="font-size: 16px; line-height: 1.5; color: #4b5563;">
            You have received a new message from the public contact form on the OSCA web application.
        </p>

        <div style="background-color: #f3f4f6; padding: 20px; border-radius: 6px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>Sender Name:</strong> {{ $senderName }}</p>
            <p style="margin: 0 0 10px 0;"><strong>Sender Email:</strong> <a href="mailto:{{ $senderEmail }}" style="color: #2563eb;">{{ $senderEmail }}</a></p>
            <p style="margin: 0 0 10px 0;"><strong>Subject:</strong> {{ $subjectLine }}</p>
            
            <hr style="border: none; border-top: 1px solid #d1d5db; margin: 15px 0;">
            
            <p style="margin: 0 0 5px 0;"><strong>Message:</strong></p>
            <p style="margin: 0; white-space: pre-wrap; font-style: italic; color: #111827;">{{ $messageContent }}</p>
        </div>
        
        <p style="font-size: 14px; color: #6b7280; margin-bottom: 0;">
            <em>Note: You can simply click "Reply" in your email client to respond directly to the sender.</em>
        </p>
    </div>
</body>
</html>
