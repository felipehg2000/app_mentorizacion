$(document).ready(function(){
        let dataTable = new DataTable('data_table_name');
    });

function clickColumnDoneTasks(param_user_type, param_task){
    if (param_user_type == 1) { //Estudiante
        alert('Patata estudiante');
    } else if (param_user_type == 2) { //Mentor
        var rowIndex = $('data_table_name').infoCallBack;
        alert('Patata mentor ' + param_task);
    }
}

function clickColumnToDoTasks(param_user_type, param_task){
    if (param_user_type == 1) { //Estudiante
        alert('Patata estudiante');
    } else if (param_user_type == 2) { //Mentor
        alert('Patata mentor');
    }
}
