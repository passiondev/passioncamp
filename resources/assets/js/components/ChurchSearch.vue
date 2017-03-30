<template>
<div id="church-search">
    <v-select placeholder="Churches..." :on-change="onChange" :options="options"></v-select>
</div>
</template>

<script>
    import vSelect from "vue-select"

    export default {
        components: {vSelect},

        data() {
            return {
                options: [],
            }
        },

        mounted() {
            axios('/admin/organizations/search').then(resp => {
                this.options = resp.data.map(obj => {
                    return {
                        value: obj.id,
                        label: obj.name,
                    }
                })
            })
        },

        methods: {
            onChange(val) {
                this.selected = null;
                window.location = `/admin/organizations/${val.value}`;
                return false;
            },
            onSearch(search, loading) {
                console.log(search);
                if (search.length < 3) {
                    return;
                }

                loading(true);
                axios('/admin/organizations/search', {
                    params: {
                        query: 'passion',
                    }
                }).then(resp => {
                    loading(false);
                    this.options = resp.data.map(obj => {
                        return {
                            value: obj.id,
                            label: obj.name,
                        }
                    })
                })

            }
        }
    }
</script>
