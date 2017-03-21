<template>
</template>
<script>
    export default {
        data() {
            return {
                toggle: false,
                drug_toggle: false,
                drug: null,
                physical_toggle: false,
                physical: null,
                visual: null,
                hearing: null,
            }
        },
        watch: {
            drug_toggle(toggle) {
                if (toggle) {
                    setTimeout(function () {
                        document.getElementById('food_allergies_drug').focus()
                    }, 300)
                }
            },
            physical_toggle(toggle) {
                if (toggle) {
                    setTimeout(function () {
                        document.getElementById('food_allergies_physical').focus()
                    }, 300)
                }
            }
        }
    }
</script>
