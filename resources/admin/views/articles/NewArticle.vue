<template>
    <div class="container">
        <div class="row border-bottom">
            <h2 class="text-muted">ساخت مقاله جدید</h2>
        </div>
        <div class="row mt-4 panel">
            <form @submit.prevent="storeArticle">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="title">عنوان مقاله</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input id="title"
                               v-model="article.title"
                               class="form-control"
                               placeholder="عنوان مقاله"
                               type="text"></input>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="title_en">عنوان مقاله (انگلیسی)</label>
                    </div>
                    <div class="col-12 col-md-9">
                        <input id="title_en"
                               v-model="article.title_en"
                               class="form-control"
                               placeholder="عنوان مقاله انگلیسی"
                               type="text"></input>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <label class="form-check-label" for="status">
                            فعال
                            <input id="status"
                                   v-model="article.status"
                                   class="form-check-input mx-1"
                                   type="checkbox"></input>

                        </label>
                    </div>
                </div>

                <div class="row mt-3">
                    <div id="editor" dir="ltr"></div>
                </div>

                <div class="row mt-3 border-top">
                    <div class="col-12 mt-3">
                        <button class="btn btn-success" type="submit">ثبت مقاله</button>

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
                content: null,
                status: true,
            }
        }
    },

    methods: {
        storeArticle() {

        }
    },
    mounted() {
        let quil = new Quill('#editor', {
            theme: 'snow'
        });
    }
}
</script>

<style scoped>

</style>
