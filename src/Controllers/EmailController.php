<?php

namespace Emails\Controllers;

use Emails\Abstracts\Controller;
use Emails\Models\EmailModel;
use Emails\Validators\NewEmailValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmailController extends Controller
{
    private EmailModel $emailModel;

    public function __construct(EmailModel $emailModel)
    {
        $this->emailModel = $emailModel;
    }

    public function getInboxEmails(Request $request, Response $response)
    {
        $data = ['message' => 'Successfully retrieved emails', 'data' => []];
        try {
            if (!empty($request->getQueryParams()['search'])) {
                $data['data'] = $this->emailModel->searchEmails($request->getQueryParams()['search']);
            } else {
                $data['data'] = $this->emailModel->getInboxEmails();
            }
        } catch (\Exception $e) {
            $data['message'] = 'Unexpected error';
            return $this->respondWithJson($response, $data, 500);
        }
        return $this->respondWithJson($response, $data);
    }

    public function getEmail(Request $request, Response $response, array $args)
    {
        $data = ['message' => 'Successfully retrieved email', 'data' => []];
        try {
            if (!empty($args['id']) && is_numeric($args['id'])) {
                $data['data']['email'] = $this->emailModel->getEmailById($args['id']);

                if (!empty($request->getQueryParams()['replies'])) {
                    $data['data']['reply'] = $this->emailModel->getReplies($args['id']);
                }
            } else {
                $data['message'] = 'Invalid email id';
                return $this->respondWithJson($response, $data, 400);
            }

        } catch (\Exception $e) {
            $data['message'] = 'Unexpected error';
            return $this->respondWithJson($response, $data, 500);
        }
        return $this->respondWithJson($response, $data);
    }

    public function sendEmail(Request $request, Response $response)
    {
        $data = ['message' => 'Successfully sent email', 'data' => []];
        try {
            $email = $request->getParsedBody();
            if (NewEmailValidator::validate($email)) {
                $data['data']['sent'] = $this->emailModel->createEmail($email);
            } else {
                $data['data']['sent'] = false;
                $data['message'] = 'Invalid email data';
                return $this->respondWithJson($response, $data, 400);
            }
        } catch (\Exception $e) {
            $data['message'] = 'Unexpected error';
            return $this->respondWithJson($response, $data, 500);
        }
        return $this->respondWithJson($response, $data);
    }

    public function deleteEmail(Request $request, Response $response, $args)
    {
        $data = ['message' => 'Successfully deleted email', 'data' => []];
        try {
            if (!empty($args['id']) && is_numeric($args['id'])) {
                $data['data']['deleted'] = $this->emailModel->deleteEmail($args['id']);
            } else {
                $data['data']['deleted'] = false;
                $data['message'] = 'Invalid email id';
                return $this->respondWithJson($response, $data, 400);
            }
        } catch (\Exception $e) {
            $data['message'] = 'Unexpected error';
            return $this->respondWithJson($response, $data, 500);
        }
        return $this->respondWithJson($response, $data);
    }

    public function readEmail(Request $request, Response $response, $args)
    {
        $data = ['message' => 'Successfully updated email', 'data' => []];
        try {
            if (!empty($args['id']) && is_numeric($args['id'])) {
                $data['data']['updated'] = $this->emailModel->readEmail($args['id']);
            } else {
                $data['data']['updated'] = false;
                $data['message'] = 'Invalid email id';
                return $this->respondWithJson($response, $data, 400);
            }
        } catch (\Exception $e) {
            $data['message'] = 'Unexpected error';
            return $this->respondWithJson($response, $data, 500);
        }
        return $this->respondWithJson($response, $data);
    }
}