<?php

class EventsController extends Controller {

    
    public $defaultAction = 'index';
    
    public function actionIndex(){
        $this->render('/events/calendar');
    }
    
    /**
     * Returns the list of events in a json encoded format.
     * @author Travis Stroud <stroud.travis@gmail.com>
     */
    public function actionFeed() {
        $model = Events::model()->findAll();
        $events = array();
        foreach ($model as $i => $event) {
            $events[$i] = array(
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
            );
            if(!$event->all_day)
                $events[$i]['allDay'] = false;
            if($event->end)
                $events[$i]['end'] = $event->end;
        }
        echo json_encode($events);
    }

}