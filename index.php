<?php
require_once 'database.php';
require_once 'Members.php';

$db = new Database();
$member = new Members($db);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .member-tree ul {
            list-style-type: none;
            padding-left: 20px;
        }

        .member-tree ul li::before {
            content: "\25B8";
            color: #337ab7;
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Member Management System</h2>

        <div class="member-tree">
            <ul id="memberList">
            </ul>
        </div>

        <button id="addMemberButton" class="btn btn-primary">Add Member</button>

        <div id="addMemberDialog" title="Add New Member">
            <form>
                <div class="form-group">
                    <label for="parent">Parent Member:</label>
                    <select class="form-control" id="parent" name="parent">
                        <option value="">No Parent (Root Level)</option>
                        <?php echo $member->getParentOptions(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Member Name:</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <small class="form-text text-muted">Please enter a valid name (only letters allowed)</small>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#addMemberDialog").dialog({
                autoOpen: false,
                modal: true,
                buttons: {
                    "Save Changes": function() {
                        var name = $("#name").val();
                        var parent = $("#parent").val();

                        
                        if (name === "") {
                            alert("Member name cannot be empty.");
                            return;
                        }

                        $.ajax({
                            url: "add_member.php",
                            type: "POST",
                            dataType: "json",
                            data: {
                                name: name,
                                parent: parent
                            },
                            success: function(response) {
                                if (response.success) {
                                    loadMemberTree();
                                    $("#addMemberDialog").dialog("close");
                                    $("#name").val(''); 
                                    $.get("index.php", function(data) {
                                        var newOptions = $(data).find("#parent").html();
                                        $("#parent").html(newOptions);
                                    });
                                } else {
                                    alert("Error: " + response.message);
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert("Error: " + textStatus + " - " + errorThrown);
                            }
                        });
                    },
                    Cancel: function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#addMemberButton").click(function() {
                $("#addMemberDialog").dialog("open");
            });

            function loadMemberTree() {
                $.ajax({
                    url: "get_members.php", 
                    type: "GET",
                    success: function(data) {
                        $("#memberList").html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Error loading member tree: " + textStatus + " - " + errorThrown);
                    }
                });
            }

            loadMemberTree();
        });
    </script>
</body>

</html>
<?php
?>