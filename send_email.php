<?php
// Databotics Solutions - Contact Form WhatsApp Handler (PingerBot API)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// PingerBot API Configuration
$pingerbot_api_url = 'https://pingerbot.in/api/send';
$access_token = '6603d0371a303';
$instance_id = '680F51EE73CA1';
$target_number = '918848160077'; // Remove + for API

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['firstName', 'lastName', 'email', 'message'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Field '{$field}' is required"]);
        exit;
    }
}

// Sanitize input data
$firstName = htmlspecialchars(trim($input['firstName']));
$lastName = htmlspecialchars(trim($input['lastName']));
$email = filter_var(trim($input['email']), FILTER_VALIDATE_EMAIL);
$company = htmlspecialchars(trim($input['company'] ?? 'Not provided'));
$mobno = htmlspecialchars(trim($input['mobno'] ?? 'Not provided'));
$message = htmlspecialchars(trim($input['message']));

// Validate email
if (!$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Create WhatsApp message content
$whatsapp_message = "🚀 *NEW CONTACT FORM SUBMISSION*\n";
$whatsapp_message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$whatsapp_message .= "👤 *CONTACT DETAILS*\n";
$whatsapp_message .= "▪️ *Name:* {$firstName} {$lastName}\n";
$whatsapp_message .= "▪️ *Email:* {$email}\n";
$whatsapp_message .= "▪️ *Company:* {$company}\n";
$whatsapp_message .= "▪️ *Mobile:* {$mobno}\n\n";

$whatsapp_message .= "💬 *MESSAGE:*\n";
$whatsapp_message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$whatsapp_message .= "{$message}\n";
$whatsapp_message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$whatsapp_message .= "📊 *SUBMISSION INFO*\n";
$whatsapp_message .= "▪️ *Date:* " . date('F j, Y') . "\n";
$whatsapp_message .= "▪️ *Time:* " . date('g:i A T') . "\n";
$whatsapp_message .= "▪️ *IP:* " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']) . "\n\n";

$whatsapp_message .= "🏢 *Databotics Solutions*\n";
$whatsapp_message .= "Website Contact Form\n";
$whatsapp_message .= "www.databotics.in";

// Prepare WhatsApp data for PingerBot API
$whatsapp_data = [
    'number' => $target_number,
    'type' => 'text',
    'message' => $whatsapp_message,
    'instance_id' => $instance_id,
    'access_token' => $access_token
];

// Function to send WhatsApp message via PingerBot API
function sendWhatsAppMessage($api_url, $data) {
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
    ]);
    
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    
    curl_close($curl);
    
    if ($error) {
        throw new Exception('cURL Error: ' . $error);
    }
    
    $response_data = json_decode($response, true);
    
    if ($http_code !== 200) {
        $error_message = isset($response_data['message']) ? $response_data['message'] : 'Unknown API error';
        throw new Exception('PingerBot API Error (HTTP ' . $http_code . '): ' . $error_message);
    }
    
    return $response_data;
}

// Try to send WhatsApp message
try {
    $result = sendWhatsAppMessage($pingerbot_api_url, $whatsapp_data);
    
    // Log successful submission
    error_log("Contact form submission sent via WhatsApp: {$firstName} {$lastName} ({$email}) - Response: " . json_encode($result));
    
    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully via WhatsApp!',
        'response' => $result
    ]);
    
} catch (Exception $e) {
    error_log("PingerBot API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send message. Please try again later.',
        'error' => $e->getMessage()
    ]);
}
?>

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['firstName', 'lastName', 'email', 'message'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Field '{$field}' is required"]);
        exit;
    }
}

// Sanitize input data
$firstName = htmlspecialchars(trim($input['firstName']));
$lastName = htmlspecialchars(trim($input['lastName']));
$email = filter_var(trim($input['email']), FILTER_VALIDATE_EMAIL);
$company = htmlspecialchars(trim($input['company'] ?? 'Not provided'));
$mobno = htmlspecialchars(trim($input['mobno'] ?? 'Not provided'));
$message = htmlspecialchars(trim($input['message']));

// Validate email
if (!$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Create email content
$subject = "New Contact Form Submission - {$firstName} {$lastName}";

$html_content = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>New Contact Form Submission</title>
</head>
<body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8fafc;'>
    <div style='max-width: 600px; margin: 0 auto; background-color: white;'>
        <!-- Header -->
        <div style='background: linear-gradient(135deg, #7c3aed, #a855f7); padding: 40px 30px; text-align: center;'>
            <h1 style='color: white; margin: 0; font-size: 28px; font-weight: bold;'>New Contact Form Submission</h1>
            <p style='color: #e9d5ff; margin: 15px 0 0 0; font-size: 16px;'>Databotics Solutions</p>
        </div>
        
        <!-- Content -->
        <div style='background: #ffffff; padding: 40px 30px;'>
            <h2 style='color: #1f2937; margin-bottom: 25px; font-size: 24px; border-bottom: 2px solid #7c3aed; padding-bottom: 10px;'>Contact Details</h2>
            
            <table style='width: 100%; border-collapse: collapse; margin-bottom: 30px;'>
                <tr>
                    <td style='padding: 15px 20px; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #374151; background-color: #f9fafb; width: 30%;'>Name:</td>
                    <td style='padding: 15px 20px; border-bottom: 1px solid #e5e7eb; color: #6b7280;'>{$firstName} {$lastName}</td>
                </tr>
                <tr>
                    <td style='padding: 15px 20px; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #374151; background-color: #f9fafb;'>Email:</td>
                    <td style='padding: 15px 20px; border-bottom: 1px solid #e5e7eb; color: #6b7280;'>
                        <a href='mailto:{$email}' style='color: #7c3aed; text-decoration: none;'>{$email}</a>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 15px 20px; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #374151; background-color: #f9fafb;'>Company:</td>
                    <td style='padding: 15px 20px; border-bottom: 1px solid #e5e7eb; color: #6b7280;'>{$company}</td>
                </tr>
                <tr>
                    <td style='padding: 15px 20px; border-bottom: 1px solid #e5e7eb; font-weight: bold; color: #374151; background-color: #f9fafb;'>Mobile:</td>
                    <td style='padding: 15px 20px; border-bottom: 1px solid #e5e7eb; color: #6b7280;'>
                        <a href='tel:{$mobno}' style='color: #7c3aed; text-decoration: none;'>{$mobno}</a>
                    </td>
                </tr>
            </table>
            
            <h3 style='color: #1f2937; margin: 30px 0 20px 0; font-size: 20px;'>Message:</h3>
            <div style='background: #f8fafc; padding: 25px; border-radius: 12px; border-left: 5px solid #7c3aed; margin-bottom: 30px;'>
                <p style='color: #374151; line-height: 1.8; margin: 0; font-size: 16px; white-space: pre-wrap;'>{$message}</p>
            </div>
            
            <!-- Call to Action -->
            <div style='text-align: center; margin: 30px 0;'>
                <a href='mailto:{$email}' style='display: inline-block; background: linear-gradient(135deg, #7c3aed, #a855f7); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;'>Reply to {$firstName}</a>
            </div>
        </div>
        
        <!-- Footer -->
        <div style='background: #1f2937; padding: 25px 30px; text-align: center;'>
            <p style='color: #9ca3af; margin: 0; font-size: 14px; margin-bottom: 10px;'>This email was sent from the Databotics Solutions contact form</p>
            <p style='color: #6b7280; margin: 0; font-size: 12px;'>Received on: " . date('F j, Y \a\t g:i A T') . "</p>
            <p style='color: #6b7280; margin: 5px 0 0 0; font-size: 12px;'>IP Address: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']) . "</p>
            
            <div style='margin-top: 20px; padding-top: 20px; border-top: 1px solid #374151;'>
                <p style='color: #9ca3af; margin: 0; font-size: 12px;'>
                    <strong>Databotics Solutions</strong><br>
                    Kochi, Kerala, India<br>
                    Phone: +91 7356957399 | Email: info@databotics.in
                </p>
            </div>
        </div>
    </div>
</body>
</html>
";

// Prepare email data for Brevo API
$email_data = [
    'sender' => [
        'name' => $from_name,
        'email' => $from_email
    ],
    'to' => [
        [
            'email' => $to_email,
            'name' => 'Jasim - Databotics'
        ]
    ],
    'replyTo' => [
        'email' => $email,
        'name' => $firstName . ' ' . $lastName
    ],
    'subject' => $subject,
    'htmlContent' => $html_content,
    'tags' => ['contact-form', 'website']
];

// Function to send email via Brevo API
function sendEmailViaBrevo($api_key, $api_url, $email_data) {
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($email_data),
        CURLOPT_HTTPHEADER => [
            'accept: application/json',
            'api-key: ' . $api_key,
            'content-type: application/json'
        ],
    ]);
    
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    
    curl_close($curl);
    
    if ($error) {
        throw new Exception('cURL Error: ' . $error);
    }
    
    if ($http_code !== 201) {
        $response_data = json_decode($response, true);
        $error_message = isset($response_data['message']) ? $response_data['message'] : 'Unknown API error';
        throw new Exception('Brevo API Error (HTTP ' . $http_code . '): ' . $error_message);
    }
    
    return json_decode($response, true);
}

// Try to send email
try {
    $result = sendEmailViaBrevo($brevo_api_key, $brevo_api_url, $email_data);
    
    // Log successful submission
    error_log("Contact form submission sent via Brevo API: {$firstName} {$lastName} ({$email}) - Message ID: " . ($result['messageId'] ?? 'unknown'));
    
    echo json_encode([
        'success' => true,
        'message' => 'Email sent successfully!',
        'messageId' => $result['messageId'] ?? null
    ]);
    
} catch (Exception $e) {
    error_log("Brevo API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send email. Please try again later.',
        'error' => $e->getMessage()
    ]);
}
?>