<?php
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <div class="main-section">
        <div class="add-section">
            <form action="app/add.php" method="POST" autocomplete="off">
                <?php if(isset($_POST['mess']) && $_POST['mess'] == 'error'){ ?>

                <input type="text" 
                     name="title" 
                     style="border-color: #ff6666"
                     placeholder="This field is required" />
                <button type="submit">Add &nbsp; <span>&#43;</span></button>

                <?php }else{ ?>
                <input type="text" 
                     name="title" 
                     placeholder="What do you need/want to do?" />
                <button type="submit">Add &nbsp; <span>&#43;</span></button>
                <?php } ?>
            </form>
       </div>
       <?php 
          $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
       ?>
       <div class="show-todo-section">
            <?php if($todos->rowCount() <= 0){ ?>
                <div class="todo-item">
                    <div class="empty">
                        <img src="img/cat.jpeg" width="100%" />
                        <img src="img/loading.gif" width="80px">
                    </div>
                </div>
            <?php } ?>

            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <span id="<?php echo $todo['id']; ?>"
                          class="remove-to-do">x</span>
                    <button class="edit-button" data-todo-id="<?php echo $todo['id']; ?>">Edit</button>
                    <?php if($todo['checked']){ ?> 
                        <input type="checkbox"
                               class="check-box"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               checked />
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php }else { ?>
                        <input type="checkbox"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               class="check-box" />
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <br>
                    <small>created: <?php echo $todo['date_time'] ?></small> 
                </div>
            <?php } ?>
       </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function(){

            $(".edit-button").click(function() {
                const id = $(this).attr('data-todo-id');
                const title = $(this).siblings('h2').text();
                const editForm = `
                    <form action="app/edit.php" method="POST" class="edit-form">
                        <input type="hidden" name="id" value="${id}">
                        <input type="text" name="title" value="${title}">
                        <button type="submit">Save</button>
                    </form>`;
                    $(this).siblings('h2').replaceWith(editForm);

                    // Tambahkan penanganan tombol escape
                    $(document).on('keydown', function(e) {
                        if (e.key === "Escape") {
                            // Hapus formulir edit
                            $(".edit-form").replaceWith(`<h2>${title}</h2>`);
                        }
                    });
                });
            });

        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');
                
                $.post("app/remove.php", 
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');

                $.post('app/check.php', 
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('checked');
                              }else {
                                  h2.addClass('checked');
                              }
                          }
                      }
                );
            });

            $(".edit-button").click(function() {
                const id = $(this).attr('data-todo-id');
                const title = $(this).siblings('h2').text();
                const editForm = `
                    <form action="app/edit.php" method="POST" class="edit-form">
                        <input type="hidden" name="id" value="${id}">
                        <input type="text" name="title" value="${title}">
                        <button type="submit">Save</button>
                    </form>
                `;
                $(this).siblings('h2').replaceWith(editForm);
            });
        });
    </script>
</body>
</html>
