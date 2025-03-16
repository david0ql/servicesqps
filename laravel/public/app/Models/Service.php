<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Twilio\Rest\Client;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'schedule',
        'comment',
        'unity_size',
        'community_id',
        'type_id',
        'status_id',
        'cleaner_id',
        'unit_number',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function cleaner()
    {
        return $this->belongsTo(User::class, 'cleaner_id');
    }

    public function extras()
    {
        return $this->belongsToMany(Extra::class, 'extras_by_service');
    }

    public function scopeForRole($query, $user)
    {
        if ($user->hasRole('Cleaner')) {
            return $query->where('cleaner_id', $user->id);
        }

        if ($user->hasRole('Manager')) {
            $communityIds = \App\Models\Community::where('manager_id', $user->id)->pluck('id');
            return $query->whereIn('services.community_id', $communityIds);
        }
        return $query;
    }
    
    

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('for_role', function ($query) {
            $user = auth()->user();
            $query->forRole($user);
        });
        
        



        static::created(function ($service) {
            $sid = "";
            $token = "";
            $twilio_number_sender_sms = "";
            // $client = new Client($sid, $token);

            // $client->messages->create(
            //     env('ADMIN_PHONE_NUMBER'),
            //     [
            //         'from' => $twilio_number_sender_sms,
            //         'body' => 'You have a new service assigned for ' . $service->date . ' in ' . $service->community->community_name
            //     ]
            // );
        });

        static::updated(function ($service) {
            $status = Status::find($service->status_id);
            $cleaner = User::find($service->cleaner_id);
            $community = Community::find($service->community_id);
            $manager = User::find($community->manager_id);

            if ($service->wasChanged('status_id')) {
                $sid = "";
                $token = "";
                // $twilio = new Client($sid, $token);

                // if ($status->status_name == 'Pending') {
                //     $message = $twilio->messages->create(
                //         $service->cleaner->phone_number,
                //         [
                //             'from' => '',
                //             'body' => 'You have a new service assigned for ' . $service->date . ' in ' . $service->community->community_name
                //         ]
                //     );
                // } elseif ($status->status_name == 'Approved') {
                //     $message = $twilio->messages->create(
                //         $manager->phone_number,
                //         [
                //             'from' => '',
                //             'body' => 'Your service for ' . $service->date . ' in ' . $service->community->community_name . ' has been accepted, and a cleaner has been assigned.'
                //         ]
                //     );
                // } elseif ($status->status_name == 'Finished') {
                //     $message = $twilio->messages->create(
                //         $manager->phone_number,
                //         [
                //             'from' => '',
                //             'body' => 'Your service for ' . $service->date . ' in ' . $service->community->community_name . ' has been finished.'
                //         ]
                //     );
                // } else {
                //     $message = $twilio->messages->create(
                //         env('ADMIN_PHONE_NUMBER'),
                //         [
                //             'from' => '',
                //             'body' => 'The service for ' . $service->date . ' in ' . $service->community->community_name . ' has been ' . $status->status_name
                //         ]
                //     );
                // }
            }
        });
    }
}
