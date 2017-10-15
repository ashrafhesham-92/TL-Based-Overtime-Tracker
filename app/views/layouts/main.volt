<?php if($this->flashSession->output()) { ?>
<?php $this->flashSession->output(); ?>
<?php } ?>
<h1>Overtime Tracker</h1>
{% if session.has('user') %}
    <a href="{{ url('userovertime/new') }}" class="btn btn-primary">Submit Overtime</a>
    <a href="{{ url('session/logout') }}" class="btn btn-warning">Logout</a>
{% else %}
    <a href="{{ url('session/register') }}" class="btn btn-primary">Login/Register</a>
{% endif %}
<div class="page-content">
    {{ content() }}
</div>