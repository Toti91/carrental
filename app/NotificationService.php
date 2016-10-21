<?php

namespace App;


class NotificationService
{
    protected $message;
    protected $userId;
    protected $icon;
    protected $color;

    public function __construct($message = null, $userId =  null, $icon = null, $color = null)
    {
            $this->message = $message;
            $this->userId = $userId;
            $this->icon = $icon;
            $this->color = $color;
    }

    public function newNotification(){
        
        $notification = new Notification;
        $notification->user_id = $this->userId;
        $notification->notification = $this->message;
        $notification->seen = 0;
        $notification->icon = $this->icon;
        $notification->color = $this->color;
        $notification->save();

        return true;
    }

    public function desipherMessage($id){
        $noti = \App\Notification::find($id);
        if (preg_match_all('/(?<!\w)@(\w+)/', $noti->notification, $matches))
            {
                $new_notification = $noti->notification;
                $instances = $matches[1];
                // $users should now contain array: ['SantaClaus', 'Jesus']
                foreach ($instances as $instance)
                {
                    $array = explode('_', $instance);
                    
                    switch ($array[0]) {
                    case 'user':
                        $instance_user = \App\User::find($array[1]);
                        if($instance_user){ $subject = $instance_user->name; } else { $subject = "<i>User not found</i>"; }
                        $url = '<a href="/admin/user/'.$array[1].'">'.$subject.'</a>';
                        $new_notification = str_replace('@'.$instance, $url, $new_notification);
                        break;
                    case 'ticket':
                        $instance_ticket = \App\Ticket::find($array[1]);
                        if($instance_ticket){ $subject = $instance_ticket->subject; } else { $subject = "<i>Ticket not found</i>"; }
                        $url = '<a href="/admin/tickets/'.$array[1].'">'.$subject.'</a>';
                        $new_notification = str_replace('@'.$instance, $url, $new_notification);
                        break;
                    default:
                        '';
                }
            }
        }

        return $new_notification;
    }
}