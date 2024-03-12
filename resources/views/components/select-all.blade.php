<th class="text-sm p-2 text-left border-b-4 max-w-8">
    <div x-data="selectAll">
        <x-tiffey::input.checkbox label="Select all" inBlock="true" x-ref="checkbox" @change="handleChange"/>
    </div>
</th>
@script
<script>

    Alpine.data('selectAll', ()=>{
        return {
            handleChange(e){
                e.target.checked ? this.selectAll() : this.deselectAll()
            },

            init() {
                this.$wire.$watch('selectedIds', () => {
                    this.updateCheckAllState()
                })

                this.$wire.$watch('idsOnPage', () => {
                    this.updateCheckAllState()
                })
            },

            updateCheckAllState() {
                if (this.pageIsSelected()) {
                    this.$refs.checkbox.checked = true
                    this.$refs.checkbox.indeterminate = false
                } else if (this.pageIsEmpty()) {
                    this.$refs.checkbox.checked = false
                    this.$refs.checkbox.indeterminate = false
                } else {
                    this.$refs.checkbox.checked = false
                    this.$refs.checkbox.indeterminate = true
                }
            },

            pageIsSelected() {
                return this.$wire.idsOnPage.every(id => this.$wire.selectedIds.includes(id))
            },

            pageIsEmpty() {
                return this.$wire.selectedIds.length === 0
            },

            selectAll() {
                this.$wire.idsOnPage.forEach(id => {
                    if (this.$wire.selectedIds.includes(id)) return

                    this.$wire.selectedIds.push(id)
                })
            },

            deselectAll() {
                this.$wire.selectedIds = []
            },
        }
    })
</script>
@endscript