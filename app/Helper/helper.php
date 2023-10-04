<?php
    function filterEmployee($AuthUserId, $employeeUserId, $api){
        if($AuthUserId != $employeeUserId){
            if($api == "api"){
                return "403";
            }
            abort(403);
        }
    }
