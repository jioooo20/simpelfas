<?php


use App\Models\UserModel;

if (!function_exists('sendRoleNotification')) {
    /**
     * Kirim notifikasi ke user berdasarkan role atau user ID.
     *
     * @param array $roles Contoh: ['A', 'B']
     * @param string $title
     * @param string $message
     * @param string $url
     * @param array $userIds (optional) Contoh: [3, 5, 7]
     *                       Jika diisi, maka hanya kirim ke user ID tersebut (abaikan $roles)
     */
    function sendRoleNotification(array $roles, string $title, string $message, string $url, array $userIds = [])
    {
        $users = count($userIds) > 0
            ? UserModel::whereIn('user_id', $userIds)->get()
            : UserModel::whereIn('role_id', $roles)->get();

        foreach ($users as $user) {
            $user->notify(new class($title, $message, $url) extends \Illuminate\Notifications\Notification {
                public $title, $message, $url;

                public function __construct($title, $message, $url)
                {
                    $this->title = $title;
                    $this->message = $message;
                    $this->url = $url;
                }

                public function via($notifiable)
                {
                    return ['database'];
                }

                public function toDatabase($notifiable)
                {
                    return [
                        'title' => $this->title,
                        'message' => $this->message,
                        'url' => $this->url,
                    ];
                }
            });
        }
    }
}
