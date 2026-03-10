<h3>Check-In Successful</h3>
<p>Visitor: <?= $appointment->name ?></p>

<a href="<?= base_url('security/gatepass/'.$appointment->id) ?>"
   class="btn btn-success">Download Gate Pass</a>