<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage User</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="addModal()"> Add New <i
                                    class="fas fa-user-plus fa-fw"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name | capitalize }}</td>
                                <td>{{ user.email }}</td>
                                <td><span
                                        class="tag tag-success">{{ user.created_at | diffForHumans | capitalize }}</span>
                                </td>
                                <td>
                                    <a href="#" @click="editModal(user)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" @click="deleteUser(user.id)">
                                        <i class="fa fa-trash-alt red"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">{{ addData ? 'Add User' : 'Edit User' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="addData?createUser():updateUser()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="text" name="email" placeholder="email"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" placeholder="password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="!addData" type="submit" class="btn btn-success">Update</button>
                            <button v-show="addData" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                addData: true,
                users: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },
        methods: {
            listUsers() {
                this.$Progress.start();
                axios.get('api/user').then(({data}) => (this.users = data.data));
                this.$Progress.finish();
            },
            createUser() {
                this.$Progress.start();
                this.form.post('api/user')
                    .then(() => {
                        Fire.$emit('refresh');
                        $('#addNew').modal('hide');
                        this.$Progress.finish();
                        Toast.fire({
                            type: 'success',
                            title: 'Signed in successfully'
                        });
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },
            updateUser() {
                this.form.put('api/user/' + this.form.id)
                    .then(() => {
                        Fire.$emit('refresh');
                        $('#addNew').modal('hide');
                        this.$Progress.finish();
                        Swal.fire(
                            'Updated!',
                            'Data has been updated successfully.',
                            'success'
                        )
                    })
                    .catch(() => {
                        this.$Progress.fail();
                        Swal.fire('Failed', 'There was something wrong', 'warning')
                    })
            },
            deleteUser(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/user/' + id)
                            .then(() => {
                                Fire.$emit('refresh');
                                Swal.fire(
                                    'Deleted!',
                                    'User has been deleted.',
                                    'success'
                                )
                            })
                            .catch(() => {
                                swal('Failed', 'There was something wrong', 'warning')
                            })
                    }
                })
            },
            addModal() {
                this.addData = true;
                this.form.reset();
                $('#addNew').modal('show');
            },
            editModal(user) {
                this.addData = false;
                this.form.reset();
                this.form.clear();
                $('#addNew').modal('show');
                this.form.fill(user);
            }
        },
        mounted() {
            this.listUsers();
            Fire.$on('refresh', () => {
                this.listUsers();
            });
        }
    }
</script>
