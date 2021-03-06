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
            <a href="{{ url('user_overtime/index/'~user.id) }}" class="btn btn-primary">
                <i class="fa fa-eye"> Overtime</i>
            </a>

            <a href="{{ url('users/delete/'~user.id) }}" class="btn btn-danger delete-user">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
    {% endfor %}
    </tbody>
</table>
<script type="text/javascript">
    $(".delete-user").click(function()
    {
        if(!confirm("Are you sure you want to delete user?"))
        {
            return false;
        }
    });
</script>
