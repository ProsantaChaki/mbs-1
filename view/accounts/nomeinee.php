<?php
session_start();
include("../../config/dbConnect.php");
include("../../config/dbClass.php");

$dbClass = new dbClass;
$customer_name = $_SESSION['customer_name'];

if(!isset($_SESSION['id']) && $_SESSION['id'] == "") header("Location:".$project_url."../view/login.php");

?>
<div class="row">
    <div class="col-md-8">
        <div class="x_panel">
            <div class="x_title">
                <h2>Account Information</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div style="height:250px; width:100%; overflow-y:auto">
                    <table id="accounts_Table" name="table_records" class="table table-bordered  responsive-utilities jambo_table table-striped  table-scroll ">
                        <thead >
                        <tr class="headings">
                            <th class="column-title" width="25%">Name</th>
                            <th class="column-title text-right" width="20%">Relation</th>
                            <th class="column-title text-right" width="30%">Account Number</th>
                            <th class="column-title" width="8%">View</th>
                            <th class="column-title" width="8%">Edit</th>
                            <th class="column-title" width="9%">Delete</th>


                        </tr>
                        </thead>
                        <tbody id="account_table_body" class="scrollable">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="x_panel account_entry_cl">
            <div class="x_title">
                <h2 id="headerNominee" >Create New Nominee</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link" id="toggle_form" onclick="nomineeView()"><i class="fa fa-chevron-down"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="iniial_collapse" style="display: none">
                <br />
                <form id="nominee_form" name="nominee_form" enctype="multipart/form-data" class="form-horizontal form-label-left">
                    <h3 id="from_header" style="text-align: center; padding-bottom: 15px">Create Nominee</h3>
                    <input type="hidden" id="Nominee_id" name="Nominee_id" required value="0"/>
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-6">Nominee Name</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="n_name" name="n_name" required class="form-control col-lg-12"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-6">Relationship with Nominee</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="relation" name="relation" required  class="form-control col-lg-12"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-6">Nominee's NID No</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="nid" name="nid" required  class="form-control col-lg-12"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-6">Account Number</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="account_number" required id="account_number" class="form-control">
                                    <option value="0">Select Option</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="button">
                        <label class="control-label col-md-3 col-sm-3 col-xs-6"></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" id="saveBtn" class="btn btn-success">Submit</button>
                            <button type="button" id="clearBtn" class="btn btn-primary">Clear</button>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="form_submit_error" class="text-center" style="display:none"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>



<script>

    //------------------------------------- grid table codes --------------------------------------
    /*
    develped by @moynul
    =>load grid
    */

//load all nominees list ........kajol

    $(document).ready(function (){
        var url = project_url+"controller/nomeineeController.php";


        load_data = function load_data(){
            $.ajax({
                url: url,
                dataType: "json",
                type: "post",
                async:false,
                data: {
                    q: "grid_data",
                },
                success: function(data){
                    var html = "";
                    // for  showing grid's no of records from total no of records
                    var records_array = data.records;
                    $('#accounts_Table tbody tr').remove();
                    //$("#search_accounts_button").toggleClass('active');
                    if(!jQuery.isEmptyObject(records_array)){
                        // create and set grid table row
                        var colums_array=["id*identifier*hidden","n_name","relation","account_number"];
                        // first element is for view , edit condition, delete condition
                        // "all" will show /"no" will show nothing
                        var condition_array=["all","","all", "","",""];
                        // create_set_grid_table_row(records_array,colums_array,int_fields_array, condition_arraymodule_name,table/grid id, is_checkbox to select tr );
                        // cauton: not posssible to use multiple grid in same page
                        create_set_grid_table_row(records_array,colums_array,condition_array,"account","accounts_Table", 0);
                        // show the showing no of records and paging for records
                    }
                    // if the table has no records / no matching records
                    else{
                        grid_has_no_result( "accounts_Table",3);
                    }
                }
            });

        }

    });

    //view edit delete ............kajol
    var nomineeId = 0;

    function load_nominee_profile(id , readable){
        //alert(id)
        $.ajax({
            url: project_url+"controller/nomeineeController.php",
            dataType: "json",
            type: "post",
            async:false,
            data: {
                q: "get_nominee_info",
                id: id
            },
            success: function(data){
                //alert('ssss')
                document.getElementById('iniial_collapse').style.display= 'block';
                //document.getElementById('headerNominee').innerHTML='Update Nominee';


                //alert('ok')
                //alert(data.records.id)
                //document.getElementById('Nominee_id').value= data.records.id;
                $('#Nominee_id').val(data.records.id);
                $('#n_name').val(data.records.n_name);
                $('#relation').val(data.records.relation);
                $('#nid').val(data.records.nid);
                $('#account_number').val(data.records.account_number);
            }
        });
        //alert(project_url+"controller/customerController.php")

    }

    function view_nominee(id){
        //alert(id)
        document.getElementById('from_header').innerHTML = 'View Nominee';
        document.getElementById('button').style.display = 'none';
        load_nominee_profile(id , 'readable')
    }
    function edit_nominee(id){
        //alert(id)
        document.getElementById('from_header').innerHTML = 'Update Nominee';
        document.getElementById('button').style.display = 'block';
        document.getElementById('saveBtn').innerHTML = 'Update';
        load_nominee_profile(id , '')
    }
    function delete_nominee(id){
        //alert(id)
        document.getElementById('from_header').innerHTML = 'Delete Nominee';
        document.getElementById('button').style.display = 'block';
        document.getElementById('saveBtn').innerHTML = 'Delete';
        document.getElementById('saveBtn').value = 'Delete';
        load_nominee_profile(id , '')
    }


    //<!-- ------------------------------------------end --------------------------------------->

    load_account_list();
    // load account list..........kajol
    function load_account_list(){
        //alert('acc')

        $.ajax({
            url: project_url+"controller/accountsController.php",
            type:'POST',
            dataType: "json",
            async:false,
            data: "q=load_account_list",
            success: function(data){
                //alert('ss')
                if(!jQuery.isEmptyObject(data.records)){

                    $('#account_number').find('option').not(':first').remove();
                    var combo = "";
                    $.each(data.records, function(i,data){
                        combo +='<option value="'+data.account_no+'">'+data.account_no+'</option>';
                    });
                    $('#account_number').append(combo);
                }
            }
        });
    }


    function  nomineeView(){
        clear_form();
        if(document.getElementById('Nominee_id').value <1){
            if(document.getElementById('iniial_collapse').style.display =='none'){
                document.getElementById('iniial_collapse').style.display= 'block';
            }
            else{
                document.getElementById('iniial_collapse').style.display= 'none';
            }
        }
        else {
            document.getElementById('Nominee_id').value = -1;
            document.getElementById('iniial_collapse').style.display= 'block';

        }

    }

    //<!-- -------------------------------Form related functions ------------------------------->

    /*
    develped by @moynul
    =>form submition for add/edit
    =>clear form
    =>load data to edit
    =>delete record
    =>view
    */
    $(document).ready(function () {
        var url = project_url+"controller/nomeineeController.php";
        //insert nominee .............kajol
        // save and update for public post/notice
        $('#saveBtn').click(function(event){
            event.preventDefault();
            //alert(document.getElementById('Nominee_id').value)


            var formData = new FormData($('#nominee_form')[0]);


            if(document.getElementById('saveBtn').innerHTML == 'Delete'){
                formData.append("q","delete_nominee");
            }
            else {
                formData.append("q","insert_update_nominee");
            }

            if($.trim($('#account_number').val()) == ""){
                success_or_error_msg('#form_submit_error','danger',"Please Insert Account No","#account_number");
            }
            else{
                $.ajax({
                    url: url,
                    type:'POST',
                    data:formData,
                    async:false,
                    cache:false,
                    contentType:false,
                    processData:false,
                    success: function(data){
                        $('#saveBtn').removeAttr('disabled','disabled');
                        //alert(data)

                        if($.isNumeric(data)==true && data==7){
                            success_or_error_msg('#form_submit_error',"danger","Please Insert Unique Account No","#account_no" );
                        }
                        else if($.isNumeric(data)==true && data>0){
                            success_or_error_msg('#form_submit_error',"success","Save Successfully");
                            load_data("");
                            clear_form();
                        }
                        else{
                            success_or_error_msg('#form_submit_error',"danger","Not Saved...");
                        }
                    }
                });
            }
        })


        // clear function to clear all the form value
        clear_form = function clear_form(){
            $("#nominee_form").trigger('reset');
            load_new_account_no();
        }

        // on select clear button
        $('#clearBtn').click(function(){
            clear_form();
        });

        // load data initially on page load with paging
        load_data("");

        // load account new no
        load_new_account_no = function load_new_account_no(){
            $.ajax({
                url: url,
                type:'POST',
                async:false,
                data: "q=new_account_no",
                success: function(data){
                    $('#account_no').val(data);
                }
            });
        }

        //function for load new account no
        load_new_account_no();

    });


    //<!-- ------------------------------------------end --------------------------------------->
</script>