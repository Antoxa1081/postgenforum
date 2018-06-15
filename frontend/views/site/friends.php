<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#fr">Friends</a></li>
  <li><a data-toggle="tab" href="#frreq">Requests</a></li>
</ul>
<div class="tab-content">
  <div id="fr" class="tab-pane fade in active">
    <h3>My friends</h3>
    <p>Some content.</p>
  </div>
  <div id="frreq" class="tab-pane fade">
    <h3>Friend Requests</h3>
    <p>Some content in menu 1.</p>
  </div>
</div> 

<script>
// Select tab by name
$('.nav-tabs a').click(function(){
    $('.nav-tabs a[href="#fr"]').tab('show');
})
// Select tab by name
$('.nav-tabs a').click(function(){
    $('.nav-tabs a[href="#frreq"]').tab('show');
});
</script>
