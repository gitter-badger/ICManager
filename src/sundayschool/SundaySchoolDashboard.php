<?php
require "../Include/Config.php";
require "../Include/Functions.php";
require "../Service/SundaySchoolService.php";

$sundaySchoolService = new SundaySchoolService();

$kidsWithoutClasses = $sundaySchoolService->getKidsWithoutClasses();
$classStats = $sundaySchoolService->getClassStats();
$classes = 0;
$teachers = 0;
$kids = 0;
$families = 0;
$maleKids = 0;
$femaleKids = 0;
$familyIds = array();
foreach ($classStats as $class) {
  $classes++;
  $kids = $kids + $class['kids'];
  $teachers = $teachers + $class['teachers'];
  $classKids = $sundaySchoolService->getKidsFullDetails($class["id"]);
  foreach ($classKids as $kid) {
    array_push($familyIds, $kid["fam_id"]);
    if ($kid["kidGender"] == "1") {
      $maleKids++;
    } else if ($kid["kidGender"] == "2") {
      $femaleKids++;
    }
  }
}


// Set the page title and include HTML header
$sPageTitle = gettext("Panel de Niños y Jóvenes");
require "../Include/Header.php";

?>
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua"><i class="fa fa-gg"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Grupos</span>
        <span class="info-box-number"> <?= $classes ?> <br/></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-olive"><i class="fa fa-group"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Guías</span>
        <span class="info-box-number"> <?= $teachers ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-orange"><i class="fa fa-child"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Jóvenes</span>
        <span class="info-box-number"> <?= $kids ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-gray"><i class="fa fa-user"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Familias</span>
        <span class="info-box-number"> <?= count(array_unique($familyIds)) ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><i class="fa fa-male"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Chicos</span>
        <span class="info-box-number"> <?= $maleKids ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-fuchsia"><i class="fa fa-female"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Chicas</span>
        <span class="info-box-number"> <?= $femaleKids ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
</div><!-- /.row -->
<div class="row">
  <div class="col-lg-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Reportes de Niños y Jóvenes</h3>
      </div>
      <div class="box-body">
        <p>
          <a href="SundaySchoolReports.php"><?php echo gettext('Lista de Clases y Asistencia'); ?></a><br/>
          <?php echo gettext('Genera listas de clases y hojas de asistencia'); ?>
        </p>
        <p>
          <a href="SundaySchoolClassListExport.php">Exportar a CSV</a><br/>
          <?php echo gettext('Exporta todas las clases, jóvenes y padres a un archivo CSV.'); ?>
        </p>
      </div>
    </div>
  </div>
  <!-- ./col -->
</div>
<div class="box box-info">
  <div class="box-header">
    <h3 class="box-title">Grupos de Niños y Jóvenes</h3>
  </div>
  <div class="box-body">
    <table id="sundayschoolMissing" class="table table-striped table-bordered data-table" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th></th>
        <th>Grupo</th>
        <th>Guía</th>
        <th>Jóvenes</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($classStats as $class) { ?>
        <tr>
          <td><a href='SundaySchoolClassView.php?groupId=<?= $class['id'] ?>'>
            <span class="fa-stack">
            <i class="fa fa-square fa-stack-2x"></i>
            <i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
          </td>
          <td><?= $class['name'] ?></td>
          <td><?= $class['teachers'] ?></td>
          <td><?= $class['kids'] ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>


<div class="box box-danger">
  <div class="box-header">
    <h3 class="box-title">Niños y Jóvenes sin Grupo</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="sundayschoolMissing" class="table table-striped table-bordered data-table" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th></th>
        <th>Primer Nombre</th>
        <th>Apellido</th>
        <th>Fecha Nac</th>
        <th>Edad</th>
        <th>Dirección Casa</th>
      </tr>
      </thead>
      <tbody>
      <?php

      foreach ($kidsWithoutClasses as $child) {
        extract($child);
        $birthDate = "";
        if ($birthYear != "") {
          $birthDate = $birthDay . "/" . $birthMonth . "/" . $birthYear;
        }

        echo "<tr>";
        echo "<td><a href='../PersonView.php?PersonID=" . $kidId . "'>";
        echo "	<span class=\"fa-stack\">";
        echo "	<i class=\"fa fa-square fa-stack-2x\"></i>";
        echo "	<i class=\"fa fa-search-plus fa-stack-1x fa-inverse\"></i>";
        echo "	</span></a></td>";
        echo "<td>" . $firstName . "</td>";
        echo "<td>" . $LastName . "</td>";
        echo "<td>" . $birthDate . "</td>";
        echo "<td>" . FormatAge($birthMonth, $birthDay, $birthYear, "") . "</td>";
        echo "<td>" . $Address1 . " " . $Address2 . " " . $city . " " . $state . " " . $zip . "</td>";
        echo "</tr>";
      }

      ?>
      </tbody>
    </table>
  </div>
</div>

<?php require "../Include/Footer.php" ?>
