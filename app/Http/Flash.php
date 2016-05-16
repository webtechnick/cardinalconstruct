<?php

namespace App\Http;

class Flash
{

    /**
     * The worker horse of the class, generate a session flash
     * with variables
     *
     * @param  string $message Actual message to display
     * @param  array  $options [description]
     * @return void
     */
    public function create($message, $options = [], $key = 'flash_message')
    {
        $options = array_merge([
            'message' => $message,
            'title' => 'Info',
            'type' => 'info',
        ], (array) $options);

        // Load into the session
        session()->flash($key, $options);
    }

    public function info($message, $title = 'Information')
    {
        return $this->create($message, ['title' => $title, 'type' => 'info']);
    }

    /**
     * Shortcut success message
     *
     * @param  [type] $message [description]
     * @param  string $title   [description]
     * @return [type]          [description]
     */
    public function success($message, $title = 'Success!')
    {
        return $this->create($message, ['title' => $title, 'type' => 'success']);
    }

    /**
     * Shortcut success message
     *
     * @param  [type] $message [description]
     * @param  string $title   [description]
     * @return [type]          [description]
     */
    public function error($message, $title = 'Error!')
    {
        return $this->create($message, ['title' => $title, 'type' => 'error']);
    }

    /**
     * Overlay message, user has to click create
     *
     * @param  [type] $message [description]
     * @param  string $title   [description]
     * @return [type]          [description]
     */
    public function overlay($message, $title = 'Info', $type = 'info')
    {
    	return $this->create($message, [
    		'title' => $title,
    		'type' => $type,
    		'message' => $message
    	], 'flash_message_overlay');
    }
}
