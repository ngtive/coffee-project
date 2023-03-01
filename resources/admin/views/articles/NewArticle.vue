<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="text-muted">ساخت مقاله جدید</h2>
        </div>
        <div v-loading="loading" class="mt-4 panel">
            <form class="row" @submit.prevent="storeArticle">
                <div class="col-12 col-md-6 col-lg-3 mb-3">
                    <label class="form-label required" for="title">عنوان مقاله</label>
                    <el-input id="title" v-model="article.title"
                              placeholder="عنوان مقاله"
                              size="mini"
                              type="text"></el-input>
                    <span v-if="validation.title" class="text-danger">
                        {{ validation.title[0] }}
                    </span>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-3">
                    <label class="form-label required" for="title_en">عنوان مقاله (انگلیسی)</label>
                    <el-input id="title_en"
                              v-model="article.title_en"
                              placeholder="عنوان مقاله انگلیسی"
                              size="mini"
                              type="text"></el-input>
                    <span v-if="validation.title_en" class="text-danger">
                        {{ validation.title_en[0] }}
                    </span>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-3">
                    <label class="form-label required">عکس کاور مقاله</label>
                    <input ref="cover"
                           class="form-control form-control-file form-control-sm"
                           type="file">
                    <span v-if="validation.cover" class="text-danger">
                        {{ validation.cover[0] }}
                    </span>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <label class="form-check-label ">
                            فعال
                            <el-checkbox
                                v-model="article.status"
                                class="mx-1"></el-checkbox>
                        </label>
                    </div>
                </div>

                <div class="row mt-3 mb-5">
                    <div id="editor" ref="editor" dir="rtl"></div>
                </div>


                <div class="row mt-3">
                    <div class="col-12 mt-3 border-top pt-2">
                        <el-button native-type="submit" size="medium" type="primary">
                            <em class="fa fa-plus me-1"></em>
                            انتشار
                        </el-button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
export default {
    name: "NewArticle",
    data: function () {
        return {
            article: {
                title: null,
                title_en: null,
                status: true,
            },
            quil: undefined,
            loading: false,
            validation: {}
        }
    },

    methods: {
        storeArticle() {
            let content = this.quil.root.innerHTML;
            let form = new FormData();
            this.loading = true;


            this.$swal({
                title: 'انتشار مقاله',
                icon: 'warning',
                text: 'آیا از انتشار مقاله اطمینان دارید؟',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'تایید',
                cancelButtonText: 'انصراف',
            })
                .then((sw) => {
                    if (sw.isConfirmed) {

                        if (this.$refs.cover.files.length > 0) {
                            form.set('cover', this.$refs.cover.files[0]);
                        } else {

                            return;
                        }

                        axios.post('/api/admin/articles', {})

                    } else {
                        this.loading = false;
                    }
                })
                .catch(() => {
                    this.loading = false;
                })
        }
    },
    mounted() {
        let quil = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{'header': [1, 2, 3, 4, 5, 6, false]}],
                    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                    ['blockquote'],

                    [{'header': 1}, {'header': 2}],               // custom button values
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{'direction': 'rtl'}],                         // text direction


                    [{'color': []}, {'background': []}],          // dropdown with defaults from theme
                    [{'font': ['ir-sns']}],
                    [{'align': []}],

                    ['clean'],                                        // remove formatting button,
                    ['image']

                ]
            }
        });

        this.quil = quil;
    }
}
</script>

<style>
.ql-toolbar {
    direction: ltr;
}

#editor h1 {

}
</style>
