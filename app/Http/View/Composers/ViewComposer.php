<?php


namespace App\Http\View\Composers;


use App\Cause;
use App\Complaint;
use App\ComplaintReply;
use App\Consultation;
use App\Message;
use App\Offer;
use App\Setting;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ViewComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if(Auth::check()){
            $settings = Setting::query()->first();
            $usersCount = User::query()->count();
            $complaintsCount = Complaint::query()->count();
            $causesMessagesCount = 0;
            $consultationsMessagesCount = 0;
            $clientsMessagesCount = 0;
            if(auth()->user()->role == "admin"){
                $causesCount = Cause::query()->count();
                $consultationsCount = Consultation::query()->count();
                $adminsIds = User::where('role','admin')->pluck('id');
                $notifications = ComplaintReply::query()->where('status',0)
                    ->whereNotIn('user_id',$adminsIds)
                    ->orderByDesc('created_at')->get();

            }else{
                $lawyersIds = User::where('office_id',auth()->user()->id)->orWhere('id',auth()->user()->id)->pluck('id');
                $causesCount = Cause::where(function ($query) use ($lawyersIds) {
                                        $query->whereIn('lawyer_id',$lawyersIds)
                                            ->where('is_public',0);
                                    })->orWhere('is_public',1)->count();
                $consultationsCount = Consultation::where('is_publish',1)->count();
//                $lawyersIds = User::where('id',\auth()->id())->orWhere('office_id',\auth()->id())->pluck('id')->toArray();
                $notifications = Offer::where('lawyer_id',\auth()->id())->where('status','!=',0)->get();

                $causesMessagesCount = Message::where('messageable_type','App\cause')
                                                ->where('receiver_id', auth()->id())
                                                ->where('seen',0)
                                                ->count();
                $consultationsMessagesCount = Message::where('messageable_type','App\Consultation')
                    ->where('seen',0)
                    ->where('receiver_id', auth()->id())
                                                    ->count();
                $clientsMessagesCount = Message::where('messageable_type','App\User')
                    ->where('receiver_id', auth()->id())
                    ->where('seen',0)
                    ->count();
            }
            $view->with([
                'complaintsCount'=>$complaintsCount,
                'consultationsCount'=>$consultationsCount,
                'causesCount'=>$causesCount,
                'usersCount'=>$usersCount,
                'causesMessagesCount'=>$causesMessagesCount,
                'consultationsMessagesCount'=>$consultationsMessagesCount,
                'clientsMessagesCount'=>$clientsMessagesCount,
                'notifications'=>$notifications,
                'settings'=>$settings,
            ]);
        }


    }
}
