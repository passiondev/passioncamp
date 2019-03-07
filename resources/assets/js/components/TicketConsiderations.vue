<template>
    <fieldset class="form-group">
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" v-model="food_toggle">
                Food Allergies
            </label>
        </div>
        <div class="pl-4 mb-2" v-if="food_toggle">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="nut" class="form-check-input" v-model="considerations.nut">
                    Peanut/Nut Allergy
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="vegetarian" class="form-check-input" v-model="considerations.vegetarian">
                    Vegetarian
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="vegan" class="form-check-input" v-model="considerations.vegan">
                    Vegan
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="gluten" class="form-check-input" v-model="considerations.gluten">
                    Gluten/Celiac
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="dairy" class="form-check-input" v-model="considerations.dairy">
                    Dairy Allergy
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="true" class="form-check-input" v-model="other_toggle">
                    <input v-if="other_toggle" type="text" ref="considerations_other" placeholder="Other..." class="form-control form-control-sm" v-model="considerations.other">
                    <template v-else>Other</template>
                </label>
            </div>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" v-model="accessibility_toggle">
                Medical/Accessibility
            </label>
        </div>
        <div class="pl-4 mb-2" v-if="accessibility_toggle">
            <div class="form-check">
                <label class="form-check-label w-100">
                    <input type="checkbox" class="form-check-input" v-model="drug_toggle">
                    <input v-if="drug_toggle" type="text" ref="considerations_drug" placeholder="Please list name of drug..." class="form-control form-control-sm" v-model="considerations.drug">
                    <template v-else>Drug Allergy</template>
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label w-100">
                    <input type="checkbox" class="form-check-input" v-model="physical_toggle">
                    <input v-if="physical_toggle" type="text" ref="considerations_physical" placeholder="Please provide more info..." class="form-control form-control-sm" v-model="considerations.physical">
                    <template v-else>Physical Impairment</template>
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="visual" class="form-check-input" v-model="considerations.visual">
                    Visual Impairment
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="hearing" class="form-check-input" v-model="considerations.hearing">
                    Hearing Impairment
                </label>
            </div>
        </div>
        <hidden-input v-if="inputName" :name="inputName" :value="considerations"></hidden-input>

    </fieldset>
</template>
<script>
    import HiddenInput from './HiddenInput'

    export default {
        components: {HiddenInput},
        props: {
            considerations: Object,
            inputName: {
                type: String,
                default: null,
            }
        },
        data() {
            return {
                food_toggle: Object.keys(_.pick(this.considerations, ['nut', 'vegetarian', 'vegan', 'gluten', 'dairy', 'other'])).length,
                accessibility_toggle: Object.keys(_.pick(this.considerations, ['drug', 'physical', 'visual', 'hearing'])).length,
                other_toggle: Object.keys(_.pick(this.considerations, ['other'])).length,
                drug_toggle: Object.keys(_.pick(this.considerations, ['drug'])).length,
                physical_toggle: Object.keys(_.pick(this.considerations, ['physical'])).length,
                htmlConsiderations: {},
            }
        },
        watch: {
            considerations: {
                handler: function(considerations) {
                    this.$emit('input', considerations)
                    if (this.inputName) this.htmlConsiderations = considerations
                },
                deep: true,
                immediate: true,
            },
            other_toggle(toggle) {
                if (! toggle) return

                this.$nextTick(() => {
                    this.$refs.considerations_other.focus()
                })
            },
            drug_toggle(toggle) {
                if (! toggle) return

                this.$nextTick(() => {
                    this.$refs.considerations_drug.focus()
                })
            },
            physical_toggle(toggle) {
                if (! toggle) return

                this.$nextTick(() => {
                    this.$refs.considerations_physical.focus()
                })
            }
        }
    }
</script>
