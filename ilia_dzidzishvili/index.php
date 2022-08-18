<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Week-2</title>
   <link rel="stylesheet"  href="styles.css">
</head>
<body>

   <?php 
      // $uploadFileName = null;
      $response = null;
      $repositories = null;
      $followers = null;
      $message = null;
      $ispost = false;
      if(isset($_POST["username"]) || isset($_POST["repo-foll"])) 
      {
         $ispost = true;
         if($_POST["repo-foll"] == 1 || $_POST["repo-foll"] == 3)
            $repositories = curl2($_POST["username"], 'repos');

         if($_POST["repo-foll"] == 2 || $_POST["repo-foll"] == 3)
            $followers = curl2($_POST["username"], 'followers');                  
      }

      function curl2($username, $uri){
         $url = 'https://api.github.com/users/'.$username.'/'.$uri;
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_HTTPHEADER, ['User-Agent: https://api.github.com/meta']);
         $response = curl_exec($curl);
         $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
         curl_close($curl);
         // print_r($response );exit;
         $response = json_decode($response);
         if(isset($response->message) && $response->message == 'Not Found')
            return null;
         return $response;
      }
   ?>

   <div class="container">
      <h1>Bitoid week 2</h1>

      <form method="post" action="index.php" enctype="multipart/form-data">
         <label for="username">Enter github user name</label>
         <input name="username" id="username" value="<?= isset($_POST["username"])?$_POST["username"]:''?>">
         <div class="form-group">
            <input type="radio" name="repo-foll" id="repositories" value="1" checked>
            <label for="repositories">Repositories</label>
         </div>
         <div class="form-group">
            <input type="radio" name="repo-foll" id="followers" value="2">
            <label for="followers">Followers</label>
         </div> 
         <div class="form-group">
            <input type="radio" name="repo-foll" id="both" value="3">
            <label for="both">Both</label>
         </div>        
         <button type="submit">Get data</button>
      </form>
   </div>

   <?php if($ispost): ?>
      <?php if($repositories === null && $followers === null): ?>
         <div class="container">
            <div class="error-messages">
               Username not found
            </div>
         </div>

      <?php else: ?>

         <?php if($repositories): ?>
            <div class="container">
               <h3>Repositories</h3>
               <table id="repos">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Name</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach($repositories as $i=>$repo):?>
                        <tr>
                           <td><?= $i+1 ?></td>
                           <td><a href="<?= $repo->html_url ?>" target="_blank"><?= $repo->html_url ?></a></td>
                        </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         <?php endif; ?>

         <?php if($followers): ?>
            <div class="container">
               <h3>Followers</h3>
               <table id="followers">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach($followers as $i=>$follower):?>
                        <tr>
                           <td><?= $i+1 ?></td>
                           <td><img src="<?= $follower->avatar_url?>" height="70"></td>
                           <td><a href="<?= $follower->html_url ?>" target="_blank"><?= $follower->login ?></a></td>
                        </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         <?php endif; ?>

      <?php endif; ?>
   <?php endif; ?>

</body>
</html>

