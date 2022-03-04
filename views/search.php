 <div class="container mainContainer">
     <!-- <h1>Home Page</h1> -->
     <div class="row">
         <div class="col-md-8">
             <h2>Search Results</h2>
             <?php //echo "Hey, it is me";
             displayTweets('search');?>
         </div>
         <div class="col-md-4">
             <?php displaySearch();?>
             <hr>
             <?php displayTweetBox();?>
         </div>
     </div>
 </div>