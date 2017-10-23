<?php if($this->flashSession->output()) { ?>
<?php $this->flashSession->output(); ?>
<?php } ?>
<h1 style="background-color: lightblue;">Overtime Tracker</h1>
{% if session.has('user') %}
    {% if session.get('user').rule_id == memberRule %}
        <h2>{{ session.get('user').team.name }} Team Member</h2>
        <a href="{{ url('user_overtime/new') }}" class="btn btn-primary">Submit Overtime</a>
        <a href="{{ url('session/logout') }}" class="btn btn-warning">Logout</a>
    {% elseif session.get('user').rule_id == teamLeaderRule %}
        <h2>{{ session.get('user').team.name }} Team Leader</h2>
        <a href="{{ url('users') }}" class="btn btn-primary">Users</a>
        <a href="{{ url('session/logout') }}" class="btn btn-warning">Logout</a>
    {% endif %}
{% else %}
    <a href="{{ url('session/register') }}" class="btn btn-primary">Login/Register (Member)</a>
{% endif %}
<div class="page-content">
    {{ content() }}
</div>