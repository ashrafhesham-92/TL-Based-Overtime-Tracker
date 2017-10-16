<?php
/**
 * @var \Phalcon\Mvc\View\Engine\Php $this
 */
?>

<div class="page-header">
    <h1>
        Team members
    </h1>
</div>

<?php echo $this->getContent() ?>

<table class="table table-bordered" style="text-align: center;">
    <thead>
    <tr>
        <th>Email</th>
        <th>Name</th>
        <th>Rule</th>
        <th>Team</th>
        <th>Overtime Records</th>
    </tr>
    </thead>
    <tbody>
    {% for user in users %}
    <tr>
        <td>{{ user.email }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.rule.name }}</td>
        <td>{{ user.team.name }}</td>
        <td>
            <a href="{{ url('userovertime/index/'~user.id) }}" class="btn btn-primary">
                <i class="fa fa-eye"> Overtime</i>
            </a>
        </td>
    </tr>
    {% endfor %}
    </tbody>
</table>
