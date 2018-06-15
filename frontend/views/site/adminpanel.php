<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Admin panel"

?>
<script type="text/javascript">
$(document).ready(function(){
    // Отображается 1 вкладка,
    // т.к. отсчёт начинается с нуля
    $("#myTab2 li:eq(0) a").tab('show');
});
</script>

<ul id="myTab2" class="nav nav-tabs">
  <li><a data-toggle="tab" href="#panStats">Stats</a></li>
  <li><a data-toggle="tab" href="#panUsers">Users</a></li>
  <li><a data-toggle="tab" href="#panThemes">Themes</a></li>
  <li><a data-toggle="tab" href="#panThreads">Threads</a></li>
  <li><a data-toggle="tab" href="#panGroups">Groups</a></li>
</ul>

<div class="tab-content">
  <div id="panStats" class="tab-pane fade">
    <h3>Stats</h3>

  </div>
  <div id="panUsers" class="tab-pane fade in active">
    <h3>Users</h3>

  </div>
  <div id="panThemes" class="tab-pane fade">
    <h3>Themes</h3>

  </div>
  <div id="panThreads" class="tab-pane fade">
    <h3>Themes</h3>

  </div>
  <div id="panGroups" class="tab-pane fade">
    <h3>Themes</h3>

  </div>
</div>
