<?php

namespace App\_H\Response;

use App\_H\Serializer\CustomSerializer;

class ApiResponse
{
    /**
     * Additional data
     */
    private $attachment = [];

    /**
     * Check if data was attach or not
     */
    private $attachFlag = 0;

    /**
     * Response success request
     */
    public function success($message = 'OK')
    {
        return $this->withMessage($message);
    }

    /**
     * Response error request
     */
    public function fail($message = '')
    {
        $message = $message ?: 'Xảy ra lỗi, vui lòng thử lại sau';

        return $this->withMessage($message, 500);
    }

    /**
     * Response created success
     */
    public function created($message = 'Tạo thành công')
    {
        return $this->withMessage($message, 201);
    }

    /**
     * Response deleted success
     */
    public function deleted($message = 'Xóa thành công')
    {
        return $this->withMessage($message, 204);
    }

    /**
     * Return json struct
     *
     * @param string $message
     * @param int $status
     * @param array $otherData
     *
     * @return json
     */
    public function withMessage($message = 'Ok', $status = 200, $otherData = null)
    {
        if ($this->attachFlag) {
            $this->response = $this->attachment->addMeta(['message' => $message]);
            $arrResponse = $this->response->toArray();
            $this->response = $this->transformResponse($arrResponse);
        } else {
            $this->response['meta'] = $this->setMetaData(['message' => $message], $otherData);
        }

        return response()->json($this->response, $status);
    }

    /**
     * Attach and transform data to response
     *
     * @param Model $data
     * @param Transformer $transformer
     *
     * @return this
     */
    public function attach($data, $transformer, $includes = [])
    {
        $this->attachFlag = 1;
        if (sizeof($includes) > 0) {
            $this->attachment = fractal($data, $transformer, new CustomSerializer)
                ->parseIncludes($includes);
        } else {
            $this->attachment = fractal($data, $transformer, new CustomSerializer);
        }

        return $this;
    }

    /**
     * Set metadata as array
     *
     * @param array $mainData
     * @param array $otherData
     *
     * @return array
     */
    private function setMetaData($mainData, $otherData = null)
    {
        $meta = [
            'message' => $mainData['message'],
        ];

        if ($otherData != null && sizeof($otherData) == 0) {
            $meta['other'] = $otherData;
        }

        return $meta;
    }

    /**
     * Add 'data' key wraper for main data
     *
     * @param array $response
     *
     * @return array
     */
    private function transformResponse($response)
    {
        if (isset($response['meta'])) {
            $formatedResponse['meta'] = $response['meta'];
            unset($response['meta']);
        }
        $formatedResponse['data'] = $response;

        return $formatedResponse;
    }
}
