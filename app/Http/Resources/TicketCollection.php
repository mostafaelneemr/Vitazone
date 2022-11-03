<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => (int) $data->id,
                    'ticket_id' => $data->code,
                    'subject' => $data->subject,
                    'details' => $data->details,
                    'status' => $data->status,
                    'files' => $this->convertPhotos(explode(',', $data->files)),
                    'sending_date' => $data->created_at->format('Y-m-d h:m:s'),
                    'replies' => $this->ticketreplies($data->ticketreplies)

                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }

    protected function ticketreplies($ticketreplies)
    {
        foreach ($ticketreplies as $key => $ticketreply) {
            $ticketreplies[$key]['files'] = $this->convertPhotos(explode(',', $ticketreply->files));
        }
        return $ticketreplies;
    }

    protected function convertPhotos($data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            $file_detail = \App\Upload::where('id', $item)->first();
            if ($file_detail) {
                $result[$key]['path'] = api_asset($item);
                $result[$key]['name'] = $file_detail->file_original_name . '.' . $file_detail->extension;
            }
        }
        return $result;
    }
}
