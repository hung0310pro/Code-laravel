<template>
    <div class="api-calling">
        <div class="error" v-if="errors.length">
           <span v-for="(err, index) in errors" :key="index">
               {{ err }}
           </span>
            <hr>
        </div>
        <!-- dùng v-model này là để lấy được giá trị trong các ô input để lấy dữ liệu đó gửi ajax lên -->
        <label>Tạo user</label>
        <form v-on:submit.prevent="createUser($event)">
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            <input v-model="name" type="text" placeholder="name">
            <!-- dùng v-model thì phải khai báo biến trong data -->
            <input v-model="email" type="email" placeholder="email">
            <input v-model="password" type="text" placeholder="password">
            <button type="submit">Tạo tài khoản</button>
        </form>

        API CALLING
        <table id="bang1" class="table table-bordered table-striped table-hover text-center">
            <thead>
            <tr>
                <th>STT</th>
                <th>Name</th>
                <th>Email</th>
                <th>Sửa</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(prod, index) in list_users" :key="prod.id">
                <td>{{ prod.id }}</td>

                <td>
                    {{ prod.name }}
                    <input v-if="prod.isUpdate" type="text" v-model="updatename"/>
                </td>
                <td>
                    {{ prod.email }}
                    <input v-if="prod.isUpdate" type="text" v-model="updateemail"/>
                </td>
                <td v-if="!prod.isUpdate">
                    <a v-on:click="showUpdateUser(index)">Sửa</a>
                </td>
                <td v-else>
                    <a v-on:click="updateUser($event,prod.id,index)">Update Value</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: [],
                list_users: [],
                name: '',
                email: '',
                password: '',
                updateemail: '',
                updatename: ''
            }
        },
        created() {
            // gọi ở đây là kiểu khi load trang là sẽ gọi hàm kia, để get ra dữ liệu
            this.getListUsers();
        },
        methods: {
            // tạo tài khoản, sau khi tạo tài khoảng mới thì phải nhét cái tài khoản đó vào trong mảng của vuejs để vẽ ra tiếp.
            createUser(e) {
                this.errors = [];
                axios.post('/Code/Code-laravel/public/listuserapi', {name: this.name, email: this.email, password: this.password})
                    .then(response => {
                        this.list_users.push({...response.data.user, 'isUpdate': false}); // es6
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors.name

                    })
            },

            // call ajax lấy dữ liệu xong sau đó đổ dữ liệu vào mảng được khai bảo trong vuejs để in ra dữ liệu
            getListUsers() {
                axios.get('/Code/Code-laravel/public/listuserapi')
                    .then(response => {
                        this.list_users = response.data;
                        this.list_users.forEach(item => {
                            Vue.set(item, 'isUpdate', false) // add thêm cái isUpdate vào mỗi user(ở front-end thôi nhé) để check nó chỉnh sửa ở trên kia(tức là nếu nó là false thì nó là dang table, còn true thì show ra cái input text ở trên để update dữ liệu)
                        });
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors.name
                    })
            },

            // khi ấn vào nút update thì đổi cái isUpdate của dòng tương ứng thành true để show ra input để có thể chỉnh sửa
            showUpdateUser(index) {
                this.list_users[index].isUpdate = true;
            },

            // sau khi chỉnh sửa xong thì update lại dữ liệu cho cái mảng ở vuejs đồng thời cho cái isUpdate là false để lại là table bt
            updateUser(e, id,index) {
                this.errors = [];
                let url = '/Code/Code-laravel/public/listuserapi/' + id;
                axios.put(url, {name: this.updatename, email: this.updateemail})
                    .then(response => {
                       this.list_users[index].name = this.updatename;
                       this.list_users[index].email = this.updateemail;
                       this.list_users[index].isUpdate = false;
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors.name;
                    })
            }
        }
    }
</script>

<style>

</style>