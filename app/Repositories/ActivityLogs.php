<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogs
{
    private $module_name, $entity, $action, $route_name, $route_id, $auth, $comment_id;
    private $entity_id, $post_words, $user_id, $user_type, $url, $route,$request_data;

    /**
     * @param Route $route
     * @param Authentication $auth
     * @param Request $request
     */
    public function __construct(Route $route, Request $request)
    {
        $this->route = $route;

        $segmentPosition = $this->getSegmentPosition($request);
        $this->module_name = '';
        $this->user_id = 0;
        $this->user_type = $this->getGuardName($request, $segmentPosition);
        $this->route_name = "";
        $this->entity_id = 0;
        $this->url = $request->fullUrl();
    }

    /**
     * @param $entity
     * @param $action
     * @param string $post_words
     * @param int $route_id
     * @param string $route_name
     */
    public function addLog($user, $entity, $action, $post_words = "", $route_id = 0, $route_name = '', $module = '', $user_type = '',$comment_id = null)
    {
        $this->entity = ucfirst($entity);
        $this->module_name = strtolower($module);
        $this->action = $this->getproperActionAndClassName($action, $entity)['action'];
        $this->post_words = $post_words;
        $this->comment_id = $comment_id;
        if ($route_name != '') {
            $this->route_name = $route_name;
        }
        if ($user_type != '') {
            $this->user_type = $user_type;
        }
        if ($route_id > 0) {
            $this->entity_id = $route_id;
        }
        $this->saveLog($user);
    }

    /**
     * Save log
     */
    private function saveLog($user)
    {
        $log = new ActivityLog();
        $log->user_id = $user->id;
        $log->user_type = $this->user_type;
        $log->module = $this->module_name;
        $log->entity = $this->entity;
        $log->action = $this->action;
        $log->post_words = $this->post_words;
        $log->route_name = $this->route_name;
        $log->route_id = $this->entity_id;
        $log->url = $this->url;
        $log->comment_id = $this->comment_id;
        $log->request_data = $this->getRequestData();
        $log->save();

        $this->clearData();
    }

    public function addLogAuth($userId, $entity, $action, $post_words = "", $route_id = 0, $route_name = '')
    {
        $this->entity = ucfirst($entity);
        $this->action = $this->getproperActionAndClassName($action, $entity)['action'];
        $this->post_words = $post_words;
        if ($route_name != '')
            $this->route_name = $route_name;
        if ($route_id > 0)
            $this->entity_id = $route_id;

        $log = new ActivityLog();
        $log->user_id = $userId;
        $log->module = $this->module_name;
        $log->user_type = $this->user_type;
        $log->entity = $this->entity;
        $log->action = $this->action;
        $log->post_words = $this->post_words;
        $log->route_name = $this->route_name;
        $log->route_id = $this->entity_id;
        $log->user_id = $userId;
        $log->url = $this->url;
        $log->save();
    }


    /**
     * @param array $params
     * @return HTML string
     */
    public function widget($params = [])
    {
        $qry = ActivityLog::orderBy('activity_logs.created_at', 'DESC');

        if (isset($params['startDate']) && $params['startDate'] != '')
            $qry->where('created_at', '>=', $params['startDate']);

        if (isset($params['endDate']) && $params['endDate'] != '')
            $qry->where('created_at', '<=', $params['endDate']);

        if (isset($params['user_id']) && !empty($params['user_id']))
            $qry->where('user_id', $params['user_id']);

        if (isset($params['user_type']) && !empty($params['user_type']))
            $qry->where('user_type', $params['user_type']);

        if (isset($params['module']) && !empty($params['module']))
            $qry->where('module', $params['module']);

        if (isset($params['entity']) && !empty($params['entity']))
            $qry->where('entity', $params['entity']);
        $qry = $qry->with(['user' => function ($q) {
            $q->orderBy('first_name', 'asc');
        }]);


        $activity_ar = [];
        $user_ar = [];

        $log_found = false;
        $user_info = [];
        foreach ($qry->get() as $activity) {
            if($activity->user_type=='customer')
                $activity_user = $activity->customer;
                else
            $activity_user = $activity->user;
            if ($activity_user) {

                $date = date('Y-m-d H:i:s.v', strtotime($activity->created_at));
                $activity_ar [$date][$activity_user->email][] = $activity;
                if($activity->user_type=='insurer'){
                    $user_roles = $activity_user->roles->pluck('name')->toArray();
                    $role = $user_roles[0] ?? '';
                }
                else
                    $role = '';

                $user_info[$activity_user->email]["role"] = $role;
                $user_info[$activity_user->email]["fullname"] = ucwords($activity_user->first_name . ' ' . $activity_user->last_name);
                $user_info[$activity_user->email]["gravatar"] = '';
                $log_found = true;
            }
        }

        $str = '';
        foreach ($activity_ar as $date => $email) {

            $date_str = date('F d, Y', strtotime($date));
            $str .= $this->htmlDateLi($date_str);
            foreach ($email as $u_email => $e_activity) {
                $str .= $this->htmlUserLi($user_info[$u_email]["gravatar"], $user_info[$u_email]["fullname"], $user_info[$u_email]["role"]);
                foreach ($e_activity as $activity)
                    $str .= $this->htmlLi($activity);
            }
        }

        if (isset($params['type']) && $log_found && $params['type'] == 'portlet') {
            $str = '<div class="portlet light bordered" style="padding-left: 4px; padding-right: 4px;"><div class="portlet-title" style="min-height: 15px;">
                    <div class="caption" style="padding-top: 0"  >
                        <i class="icon-share font-dark hide"></i>
                        <span class="caption-subject">Recent Activities</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scroller activities" style="height: 300px;" data-always-visible="1"
                         data-rail-visible="0"><ul class="activity">' . $str . '</ul></div>
                    <div class="scroller-footer">
                        <div class="btn-arrow-link pull-right">
                            <a href="' . route('insurer.logs.activity') . '">See All Records</a>
                            <i class="icon-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>';
        } elseif (isset($params['type']) && $log_found && $params['type'] == 'ul') {
            $str = '<div class="scroller activities" style="padding: 10px;" data-always-visible="1"
                         data-rail-visible="0"><ul class="activity">' . $str . '</ul></div>';
        } else {
            $str = '<div class="scroller activities" style="padding: 10px;" data-always-visible="1"
                         data-rail-visible="0"><p>No activity found</p></div>';
        }

        //Returning complete Activity UL
        return $str;
    }

    /**
     * @param $user_avatar
     * @param $user_name
     * @return string
     */
    private function htmlUserLi($user_avatar, $user_name, $role = "")
    {
        return '<li class="user">
				<div class="avatar">
				<img src="' . $user_avatar . '">
								</div>
								<div class="heading" style="padding-left:35px;">' . $user_name . '
								<span class="role" >' . $role . '</span></div>
							  </li>';
    }

    /**
     * @param $date
     * @return string
     */
    private function htmlDateLi($date)
    {
        return '<li class="date">
								<div class="heading">' . $date . '</div>
							  </li>';
    }

    /**
     * @param $activity
     * @return string
     */
    private function htmlLi($activity)
    {
        //get li class by action
        $li_class = $this->getproperActionAndClassName($activity->action, $activity->entity)['class'];
        $date = date('F d, Y  h:i:s A', strtotime($activity->created_at));
        if($activity->user_type=='customer')
            $sentense = $activity->post_words . ' ' . $activity->action . ' by ' . $activity->customer->first_name . $activity->customer->middle_name ?? ' ' . $activity->customer->middle_name . ' ' . $activity->customer->last_name . ' ' . $this->htmlLink($activity->action, $activity);
        else
            $sentense = $activity->post_words . ' ' . $activity->action . ' by ' . $activity->user->first_name . ' ' . $activity->user->last_name . ' ' . $this->htmlLink($activity->action, $activity);

        return '<li><div class="icon ' . $li_class . '"></div>
					<div class="inn">' . $sentense . '<span class="dated">' . $date . '</span></div>
					</li>';
    }

    /**
     * @param $action
     * @param $activity
     * @return string
     */
    private function htmlLink($action, $activity)
    {
        $allowed_action = [
            'deleted'
        ];

        //Don't show link for delete
        if (!array_key_exists($action, $allowed_action)) {

            //if both route name and argument given
            if ($activity->route_name != '' && (is_numeric($activity->route_id) && $activity->route_id > 0))
                return '<a href="' . route($activity->route_name, [$activity->route_id]) . '" target="_blank"><i class="fa fa-external-link"></i></a>';

            //if only route name given
            if ($activity->route_name != '')
                return '<a href="' . route($activity->route_name, [$activity->route_id]) . '" target="_blank"><i class="fa fa-external-link"></i></a>';
        }
    }

    /**
     * @param $action
     * @return array
     */
    private function getproperActionAndClassName($action, $entity)
    {
        $sAction = $action;
        switch ($action) {
            case 'added':
            case 'add':
            case 'new':
                $sAction = 'created';
                break;
            case 'update':
            case 'edit':
            case 'updated':
            case 'modify':
                $sAction = 'modified';
                break;
            case 'deleted':
            case 'remove':
            case 'removed':
                $sAction = 'deleted';
                break;
            case 'approve':
            case 'post':
                $sAction = 'approved';
                break;
            case 'disapprove':
            case 'disapproved':
            case 'rejected':
                $sAction = 'disapproved';
                break;
            case 'reopen':
            case 'reopened':
                $sAction = 'reopened';
                break;
            case 'deferred':
                $sAction = 'deferred';
                break;
            case 'transferred':
            case 'moved':
                $sAction = 'transferred';
                break;
        }
        $li_class = [
            'created' => 'add',
            'modified' => 'edit',
            'deleted' => 'delete',
            'approved' => 'approve',
            'disapproved' => 'disapprove',
            'transferred' => 'post',
        ];
        return ['action' => $sAction, 'class' => ((array_key_exists($sAction, $li_class)) ? $li_class[$sAction] : "") . '-' . strtolower($entity)];
    }

    /**
     * Get the correct segment position based on the locale or not
     *
     * @param $request
     * @return mixed
     */
    private function getSegmentPosition(Request $request)
    {
        $segmentPosition = 3;

        if ($request->segment($segmentPosition) == 'insurer')
            return ++$segmentPosition;

        return $segmentPosition;
    }

    /**
     * @param Request $request
     * @param         $segmentPosition
     * @return string
     */
    protected function getGuardName(Request $request, $segmentPosition)
    {
        return Auth::getDefaultDriver();
    }

    public function setRequestData($request){
        $request->request->add(['request_ip' => $request->ip() ?? null,'user_agent'=>$request->userAgent() ?? null]);
        $this->request_data = json_encode($request->all());
        return $this;
    }

    public function getRequestData(){
        return $this->request_data ?? null;
    }

    public function clearData(){
        $this->request_data = null;
    }
}