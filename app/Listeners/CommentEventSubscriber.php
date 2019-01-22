<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\CommentNotification;
use Notification;

class CommentEventSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle comment created events.
     */
    public function onCommentCreated($event)
    {
        $comment = $event->comment;

        if ($comment->parent == null) {
            $users = $comment->commentable->getNotifiable();
            $details['name'] = is_a($users, 'App\User') ? $users->name : 'All';
            $details['commentOn'] = $comment->commentable->getHasCommentMessage();
        } else {
            $users = $comment->parent->commenter;
            $details['name'] = $users->name;
            $details['parentComment'] = $comment->parent->comment;
        }

        $details['commenter'] = $comment->commenter->name;
        $details['comment'] = $comment->comment;

        Notification::send($users, new CommentNotification($details));
    }

    /**
     * Handle comment deleted events.
     */
    public function onCommentDeleted($event)
    {
    }

    /**
     * Handle comment updated events.
     */
    public function onCommentUpdated($event)
    {
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Laravelista\Comments\Events\CommentCreated',
            'App\Listeners\CommentEventSubscriber@onCommentCreated'
        );

        $events->listen(
            'Laravelista\Comments\Events\CommentDeleted',
            'App\Listeners\CommentEventSubscriber@onCommentDeleted'
        );

        $events->listen(
            'Laravelista\Comments\Events\CommentUpdated',
            'App\Listeners\CommentEventSubscriber@onCommentUpdated'
        );
    }
}
