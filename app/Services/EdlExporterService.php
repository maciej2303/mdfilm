<?php


namespace App\Services;

use App\Models\ProjectElementComponentVersion;

class EdlExporterService
{
    public function getComments($request) {
        $model = ProjectElementComponentVersion::find($request->modelId);
        $comments = $request->inner ? $model->innerComments() : $model->externalComments();
        $comments = $comments->orderBy('start_time', 'asc')->get();
        $text = '';
        $line = 2;
        $first = true;
        foreach($comments as $comment) {
            if ($comment->start_time && $comment->end_time) {
                if($first) {
                   $text .= '001  001  C  V  00:00:00:00 00:00:00:00  00:00:00:00 00:00:00:00break';
                   $first = false;
                }
                $line_string = $line < 10 ? '00'.$line : ($line < 100 ? '0'.$line : $line);
                $frame = explode('.',$comment->start_time);
                if (is_array($frame) && count($frame) > 1) {
                    $frame = substr($frame[1], 0, 2);
                    $frame =  str_replace(".", "",substr($frame/4, 0, 2));
                    $frame = $frame > 9 ? $frame : '0'.$frame;
                } else {
                    $frame = '00';
                }

                $time_from = gmdate("H:i:s", $comment->start_time).':'.$frame;
                $time_to = gmdate("H:i:s", $comment->end_time).':00';
                $text .= $line_string.'  001  C  V  '.$time_from.' '.$time_to.'  '.$time_from.' '.$time_to.'break';
                $line++;
            }
        };
        return $text;
    }
}