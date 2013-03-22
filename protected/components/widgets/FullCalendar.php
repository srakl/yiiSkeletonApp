<?php

class FullCalendar extends CWidget {

    public function init() {
        $baseUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.components.widgets.FullCalendar'), false, 1);
        if (!cs()->isCssFileRegistered($baseUrl . '/fullcalendar.css') && !cs()->isScriptFileRegistered($baseUrl . '/fullcalendar.min.js')) {
            $scriptfilename = YII_DEBUG ? 'fullcalendar.js' : 'fullcalendar.min.js';
            $cssfilename = 'fullcalendar.css';
            cs()->registerScriptFile($baseUrl . '/' . $scriptfilename, CClientScript::POS_HEAD);
            cs()->registerCssFile($baseUrl . '/' . $cssfilename);
        }
        cs()->registerScript('calendar', "		
		$('#calendar').fullCalendar({
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
			events: {
                            url: '".  url('/events/feed')."',
                            cache: true
                        },
		});
		
	");
        echo "<div id='calendar'></div>";
    }

}