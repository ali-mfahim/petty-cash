<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Google_Client;
use Google_Service_Gmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GmailController extends Controller
{
    public function redirectToGoogle()
    {
        $clientId = config("project.google.client_id");
        $clientSecret = config("project.google.client_secret");
        $redirectUrl = config("project.google.redirect_url");

        $client = new Google_Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUrl);
        $client->addScope(Google_Service_Gmail::GMAIL_READONLY);
        $client->addScope(Google_Service_Gmail::GMAIL_SEND);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return redirect()->away($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {

        $clientId = config("project.google.client_id");
        $clientSecret = config("project.google.client_secret");
        $redirectUrl = config("project.google.redirect_url");

        $client = new Google_Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUrl);
        $client->authenticate($request->get('code'));

        $accessToken = $client->getAccessToken();
        Session::put('gmail_token', $accessToken);

        return redirect()->route('emails.index');
    }

    public function fetchEmails()
    {
        if (!empty(Session::get('gmail_token'))) {

            $client = new Google_Client();
            $client->setAccessToken(Session::get('gmail_token'));

            if ($client->isAccessTokenExpired()) {
                // Handle token refresh
                if (isset(Session::get('gmail_token')['refresh_token']) && !empty(Session::get('gmail_token')['refresh_token'])) {
                    $refreshToken = Session::get('gmail_token')['refresh_token'];
                    $client->fetchAccessTokenWithRefreshToken($refreshToken);
                    Session::put('gmail_token', $client->getAccessToken());
                } else {
                    $route = route("google.authorize");
                    return redirect()->to($route);
                }
            }

            $service = new Google_Service_Gmail($client);
            $emailData = $this->fetchEmailsPage($service, null);

            $data['title'] = "Emails";
            $data['emails'] = $emailData;
            return view('admin.pages.emails.index', $data);
        } else {
            $route = route("google.authorize");
            return redirect()->to($route);
        }
    }

    private function fetchEmailsPage($service, $pageToken)
    {
        $params = ['maxResults' => 10];
        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }

        $messagesResponse = $service->users_messages->listUsersMessages('me', $params);
        $emailData = [];

        foreach ($messagesResponse->getMessages() as $message) {
            $msg = $service->users_messages->get('me', $message->getId());
            $payload = $msg->getPayload();

            $emailData[] = [
                'id' => $msg->getId(),
                'snippet' => $msg->getSnippet(),
                'subject' => $this->getHeader($msg, 'Subject'),
                'from' => $this->getHeader($msg, 'From'),
                'to' => $this->getHeader($msg, 'To'),
                'cc' => $this->getHeader($msg, 'Cc'),
                'bcc' => $this->getHeader($msg, 'Bcc'),
                'date' => $this->getHeader($msg, 'Date'),
                'body' => $this->getBody($payload),
            ];
        }

        if ($messagesResponse->getNextPageToken()) {
            $emailData = array_merge($emailData, $this->fetchEmailsPage($service, $messagesResponse->getNextPageToken()));
        }

        return $emailData;
    }

    private function getHeader($message, $headerName)
    {
        $headers = $message->getPayload()->getHeaders();
        foreach ($headers as $header) {
            if ($header->getName() == $headerName) {
                return $header->getValue();
            }
        }
        return null;
    }
    private function getBody($payload)
    {
        $body = '';
        if ($payload->getBody()->getSize() > 0) {
            $body = $payload->getBody()->getData();
        } else {
            foreach ($payload->getParts() as $part) {
                if ($part->getMimeType() == 'text/html') {
                    $body = $part->getBody()->getData();
                    break;
                } elseif ($part->getMimeType() == 'text/plain') {
                    $body = nl2br($part->getBody()->getData()); // Converts new lines to <br> for plain text
                }
            }
        }

        $body = str_replace(['-', '_'], ['+', '/'], $body);
        return base64_decode($body);
    }
    // app/Http/Controllers/EmailController.php
    public function emailDetail($id)
    {
        if (!empty(Session::get('gmail_token'))) {

            $client = new Google_Client();
            $client->setAccessToken(Session::get('gmail_token'));

            if ($client->isAccessTokenExpired()) {
                // Handle token refresh
                if (isset(Session::get('gmail_token')['refresh_token']) && !empty(Session::get('gmail_token')['refresh_token'])) {
                    $refreshToken = Session::get('gmail_token')['refresh_token'];
                    $client->fetchAccessTokenWithRefreshToken($refreshToken);
                    Session::put('gmail_token', $client->getAccessToken());
                } else {
                    $route = route("google.authorize");
                    return redirect()->to($route);
                }
            }

            $service = new Google_Service_Gmail($client);

            // Fetch the specific email
            $message = $service->users_messages->get('me', $id);
            $payload = $message->getPayload();

            $emailData = [
                'id' => $message->getId(),
                'snippet' => $message->getSnippet(),
                'subject' => $this->getHeader($message, 'Subject'),
                'from' => $this->getHeader($message, 'From'),
                'to' => $this->getHeader($message, 'To'),
                'cc' => $this->getHeader($message, 'Cc'),
                'bcc' => $this->getHeader($message, 'Bcc'),
                'date' => $this->getHeader($message, 'Date'),
                'body' => $this->getBody($payload),
            ];
            // Fetch the email thread
            $thread = $service->users_threads->get('me', $message->getThreadId());
            $threadMessages = [];
            foreach ($thread->getMessages() as $threadMessage) {
                $payload = $threadMessage->getPayload();
                $threadMessages[] = [
                    'id' => $threadMessage->getId(),
                    'snippet' => $threadMessage->getSnippet(),
                    'subject' => $this->getHeader($threadMessage, 'Subject'),
                    'from' => $this->getHeader($threadMessage, 'From'),
                    'to' => $this->getHeader($threadMessage, 'To'),
                    'cc' => $this->getHeader($threadMessage, 'Cc'),
                    'bcc' => $this->getHeader($threadMessage, 'Bcc'),
                    'date' => $this->getHeader($threadMessage, 'Date'),
                    'body' => $this->getBody($payload),
                ];
            }

            $data['title'] = "Email Details";
            $data['email'] = $emailData;
            $data['thread'] = $threadMessages;
            $view = view('admin.pages.emails.show', $data)->render();
            return jsonResponse(true, $view, "Email Details", 200);
        } else {
            $route = route("google.authorize");
            return jsonResponse(false, $route, "Failed to find the details of email", 200);
        }
    }


    public function sendEmail(Request $request)
    {
        $client = new Google_Client();
        $client->setAccessToken(Session::get('gmail_token'));

        if ($client->isAccessTokenExpired()) {
            $refreshToken = Session::get('gmail_token')['refresh_token'];
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            Session::put('gmail_token', $client->getAccessToken());
        }

        $service = new Google_Service_Gmail($client);

        $strRawMessage = "From: <your-email@gmail.com>\r\n";
        $strRawMessage .= "To: <{$request->input('to')}>\r\n";
        $strRawMessage .= "Subject: {$request->input('subject')}\r\n\r\n";
        $strRawMessage .= "{$request->input('message')}";

        $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');

        $msg = new \Google_Service_Gmail_Message();
        $msg->setRaw($mime);

        $service->users_messages->send('me', $msg);

        return redirect()->back()->with('success', 'Email sent successfully!');
    }
}
