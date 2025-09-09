namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewContentCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function broadcastOn()
    {
        return new Channel('realtime-updates');
    }

    public function broadcastAs()
    {
        return 'NewContentCreated';
    }
}
