namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['reportable_type', 'reportable_id', 'reporter_id', 'reason', 'admin_notes', 'status'];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id')->withDefault([
            'name' => 'Former Citizen'
        ]);
    }

    public function reportable()
    {
        return $this->morphTo();
    }
}
