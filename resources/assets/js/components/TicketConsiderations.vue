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
                    <input type="checkbox" name="considerations[nut]" value="nut" class="form-check-input" v-model="nut">
                    Peanut/Nut Allergy
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="considerations[vegetarian]" value="vegetarian" class="form-check-input" v-model="vegetarian">
                    Vegetarian/Vegan
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="considerations[gluten]" value="gluten" class="form-check-input" v-model="gluten">
                    Gluten/Celiac
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="considerations[dairy]" value="dairy" class="form-check-input" v-model="dairy">
                    Dairy Allergy
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" value="true" class="form-check-input" v-model="other_toggle">
                    <input v-if="other_toggle" type="text" name="considerations[other]" id="considerations_other" placeholder="Other..." class="form-control form-control-sm" v-model="other">
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
                    <input v-if="drug_toggle" type="text" name="considerations[drug]" id="considerations_drug" placeholder="Please List Name Of Drug..." class="form-control form-control-sm" v-model="drug">
                    <template v-else>Drug Allergy</template>
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label w-100">
                    <input type="checkbox" class="form-check-input" v-model="physical_toggle">
                    <input v-if="physical_toggle" type="text" name="considerations[physical]" id="considerations_physical" placeholder="Please Provide More Info..." class="form-control form-control-sm" v-model="physical">
                    <template v-else>Physical Impairment</template>
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="considerations[visual]" value="visual" class="form-check-input" v-model="visual">
                    Visual Impairment
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="considerations[hearing]" value="hearing" class="form-check-input" v-model="hearing">
                    Hearing Impairment
                </label>
            </div>
        </div>
    </fieldset>
</template>
<script>
    export default {
        props: ['considerations'],
        created() {
            console.log();
        },
        data() {
            return {
                food_toggle: Object.keys(_.pick(this.considerations, ['nut', 'vegetarian', 'gluten', 'dairy', 'other'])).length,
                accessibility_toggle: Object.keys(_.pick(this.considerations, ['drug', 'physical', 'visual', 'hearing'])).length,
                other_toggle: Object.keys(_.pick(this.considerations, ['other'])).length,
                drug_toggle: Object.keys(_.pick(this.considerations, ['drug'])).length,
                physical_toggle: Object.keys(_.pick(this.considerations, ['physical'])).length,
                nut: this.considerations.nut,
                vegetarian: this.considerations.vegetarian,
                gluten: this.considerations.gluten,
                dairy: this.considerations.dairy,
                other: this.considerations.other,
                drug: this.considerations.drug,
                physical: this.considerations.physical,
                visual: this.considerations.visual,
                hearing: this.considerations.hearing,
            }
        },
        watch: {
            other_toggle(toggle) {
                if (toggle) {
                    setTimeout(function () {
                        document.getElementById('considerations_other').focus()
                    }, 300)
                }
            },
            drug_toggle(toggle) {
                if (toggle) {
                    setTimeout(function () {
                        document.getElementById('considerations_drug').focus()
                    }, 300)
                }
            },
            physical_toggle(toggle) {
                if (toggle) {
                    setTimeout(function () {
                        document.getElementById('considerations_physical').focus()
                    }, 300)
                }
            }
        }
    }
</script>
