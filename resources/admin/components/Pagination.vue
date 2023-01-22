<template>
    <nav aria-label="Page navigation example" :dir="dir" id="pagination">
        <ul class="pagination">
            <li class="page-item" @click="previous">
                <a class="page-link" :class="{'disable': this.previous_disable}" href="#pagination" aria-label="Previous">
                    <span class="fa fa-caret-left" aria-hidden="true"></span>
                </a>
            </li>
            <li class="page-item"
                v-for="(page, key) in in_limit ? half_page_count : page_count"
                @click="() => change(page)"
                :key="page">
                <a class="page-link active" href="#pagination" v-if="currentPage == page">
                    {{ page }}
                </a>
                <a class="page-link" href="#pagination" v-else>
                    {{ page }}
                </a>
            </li>
            <li class="page-item" v-if="in_limit && currentPage > half_page_count && currentPage != page_count">
                <a class="page-link active" href="#pagination">
                    {{ currentPage }}
                </a>
            </li>
            <li class="page-item" v-if="in_limit">
                <a class="page-link" href="#pagination">...</a>
            </li>
            <li class="page-item" v-if="in_limit" @click="change(page_count)">
                <a class="page-link" :class="{'active': currentPage == page_count}" href="#pagination">{{ page_count }}</a>
            </li>
            <li class="page-item" @click="next">
                <a class="page-link" :class="{'disable': this.next_disable}" href="#pagination" aria-label="Next">
                    <span aria-hidden="true" class="fa fa-caret-right"></span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "Pagination",
    props: {
        dir: String,
        total: Number,
        perPage: Number,
        currentPage: Number,
        limit: {
            type: Number,
            default: 10,
        },
    },

    computed: {
        in_limit() {
            return this.page_count > this.limit;
        },
        page_count() {
            let compression = this.total / this.perPage;
            if (compression < 2)  {
                if (compression > 1) {
                    return 2
                } else {
                    return 1;
                }
            } else {
                if (compression > Math.round(compression)) {
                    return  Math.round(compression) + 1;
                } else {
                    return Math.round(compression);
                }
            }
        },

        half_page_count() {
            if (this.page_count < 15) {
                return Math.round(this.page_count / 2);
            } else {
                return 5;
            }
        },

        current_more_than_half() {
            return this.currentPage > this.half_page_count;
        },
        current_in_half() {
            return this.currentPage == this.half_page_count;
        },


        previous_disable() {
            return this.currentPage == 1;
        },
        next_disable() {
            return this.currentPage == this.page_count;
        }

    },


    methods: {
        previous() {
            if (this.currentPage === 1) {

            } else if (this.currentPage > 1) {
                this.$emit('change', this.currentPage - 1);
            }
        },

        next() {
            if (this.currentPage < this.page_count) {
                this.$emit('change', this.currentPage + 1);
            }
        },

        change(page) {
            this.$emit('change', page);
        }
    }
}
</script>

<style scoped>
.page-link.active {
    z-index: 3;
    color: white;
    background-color: #f1a651;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(253, 169, 13, 0.25);

}
.page-link {
    background-color: #f29224;
}
.page-link {
    color: white;
}

.page-link.disable {
    background-color: #e1bf98;
    cursor: default;
}
.page-link:focus {
    z-index: 3;
    color: white;
    background-color: #f29224;
    outline: 0;
    box-shadow: none;
}
.page-link:focus.active {
    z-index: 3;
    color: white;
    background-color: #f1a651;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(253, 169, 13, 0.25);
}
.page-item .page-link {
    border-radius: 5px;
}
.page-item:not(:first-child) .page-link {
    margin-left: 3px;
}
</style>
