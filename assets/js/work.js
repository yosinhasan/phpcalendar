$(document).ready(function(){
var object = null;
$(".delete").click(function(e){
    var delObject = $(this).parent();
    var id =  parseInt(delObject.find("td:first-child").text());
     if (id > 0) {
        $.ajax({
               type: 'POST',
               url: 'ajax.php',
               data: 'id=' + id + 'action=delete',
               success: function(data){
                   alert(data);
                   delObject.remove();
                }
       });
     
    }
    
});

$(".edit").click(function(e){
    var editObject = $(this).parent();
    var id =  parseInt(editObject.find("td:first-child").text());
    var name = editObject.find("td:nth-child(2)").text();
    var date = editObject.find("td:nth-child(3)").text();
    $("#editForm input[name='name']").val(name);
    $("#editForm input[name='date']").val(date);
    $("#editForm input[name='id']").val(id);
    editObject.before($("#editForm"));
    object = editObject;
    $("#editForm").show();
    return false;
});
$("#editForm button").click(function(){
    var id =  $("#editForm input[name='id']").val();
    var name = $("#editForm input[name='name']").val();
    var date = $("#editForm input[name='date']").val();
    if (id > 0) {
        $.ajax({
               type: 'POST',
               url: 'ajax.php',
               data: {'action' : 'edit',
                       'name' : name,
                       'date' : date,
                       'id' : id},
               success: function(data){
                    object.find("td:nth-child(2)").text(name);
                    object.find("td:nth-child(3)").text(date);
                    object = null;
                    $("#editForm").hide(400);
                    $("#editForm input[name='id']").val("");
                    $("#editForm input[name='name']").val("");
                    $("#editForm input[name='date']").val("");
  
                }
       });
    }
   
    return false;
    
});


});
