<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Kuis
            </h2>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[
                { label: 'Kuis & Ujian', href: '/quizzes' },
                { label: quiz.title, href: `/quizzes/${quiz.id}` },
                { label: 'Edit' }
            ]" />

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Quiz Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kuis</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Kuis <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.title"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Contoh: Kuis Matematika Bab 1"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Jelaskan tentang kuis ini..."
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.course_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Pilih Mata Pelajaran</option>
                                <option v-for="course in courses" :key="course.id" :value="course.id">
                                    {{ course.nama_mapel }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="draft">Draft (belum publish)</option>
                                <option value="published">Published (siswa bisa akses)</option>
                                <option value="closed">Closed (tidak bisa dikerjakan)</option>
                            </select>
                        </div>

                        <!-- Target Kelas -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Target Kelas
                                <span class="text-gray-400 text-xs ml-1">(kosongkan untuk semua kelas)</span>
                            </label>
                            <div class="border border-gray-300 rounded-lg p-3 max-h-40 overflow-y-auto">
                                <div v-if="kelasOptions.length === 0" class="text-gray-500 text-sm">
                                    Belum ada data kelas
                                </div>
                                <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <label v-for="kelas in kelasOptions" :key="kelas" class="flex items-center text-sm">
                                        <input
                                            type="checkbox"
                                            :value="kelas"
                                            v-model="form.kelas"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2"
                                        />
                                        {{ kelas }}
                                    </label>
                                </div>
                            </div>
                            <p v-if="form.kelas.length > 0" class="text-xs text-blue-600 mt-1">
                                {{ form.kelas.length }} kelas dipilih
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Durasi (menit)
                            </label>
                            <input
                                v-model.number="form.duration"
                                type="number"
                                min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Kosongkan jika unlimited"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Max Percobaan <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model.number="form.max_attempts"
                                type="number"
                                min="1"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Lulus (%) <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model.number="form.passing_score"
                                type="number"
                                min="0"
                                max="100"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tersedia Dari</label>
                            <input
                                v-model="form.available_from"
                                type="datetime-local"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tersedia Sampai</label>
                            <input
                                v-model="form.available_until"
                                type="datetime-local"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <label class="flex items-center">
                            <input v-model="form.randomize_questions" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                            <span class="ml-2 text-sm text-gray-700">Acak urutan soal</span>
                        </label>
                        <label class="flex items-center">
                            <input v-model="form.show_correct_answers" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                            <span class="ml-2 text-sm text-gray-700">Tampilkan jawaban benar setelah selesai</span>
                        </label>
                    </div>
                </div>

                <!-- Questions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Soal ({{ form.questions.length }})</h3>
                        <button
                            type="button"
                            @click="addQuestion"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        >
                            + Tambah Soal
                        </button>
                    </div>

                    <div v-if="form.questions.length === 0" class="text-center py-8 text-gray-500">
                        Belum ada soal. Klik "Tambah Soal" untuk memulai.
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="(question, index) in form.questions"
                            :key="index"
                            class="border border-gray-200 rounded-lg p-4"
                        >
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-semibold text-gray-900">Soal #{{ index + 1 }}</h4>
                                <button
                                    type="button"
                                    @click="removeQuestion(index)"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    Hapus
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Soal</label>
                                        <select
                                            v-model="question.type"
                                            @change="onTypeChange(index)"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        >
                                            <option value="multiple_choice">Pilihan Ganda</option>
                                            <option value="true_false">Benar/Salah</option>
                                            <option value="short_answer">Jawaban Singkat</option>
                                            <option value="essay">Essay</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Poin</label>
                                        <input
                                            v-model.number="question.points"
                                            type="number"
                                            min="1"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                                    <textarea
                                        v-model="question.question_text"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        placeholder="Tulis pertanyaan di sini..."
                                    ></textarea>
                                </div>

                                <!-- Multiple Choice Options -->
                                <div v-if="question.type === 'multiple_choice'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Jawaban</label>
                                    <div class="space-y-2">
                                        <div v-for="(option, optIndex) in question.options" :key="optIndex" class="flex gap-2">
                                            <input
                                                type="radio"
                                                :name="`correct-${index}`"
                                                :value="optIndex"
                                                v-model="question.correct_answer"
                                                class="mt-2"
                                            />
                                            <input
                                                v-model="option.text"
                                                type="text"
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg"
                                                :placeholder="`Opsi ${optIndex + 1}`"
                                            />
                                            <button
                                                v-if="question.options.length > 2"
                                                type="button"
                                                @click="removeOption(index, optIndex)"
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="addOption(index)"
                                        class="mt-2 text-sm text-blue-600 hover:text-blue-800"
                                    >
                                        + Tambah Opsi
                                    </button>
                                </div>

                                <!-- True/False -->
                                <div v-else-if="question.type === 'true_false'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jawaban Benar</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" v-model="question.correct_answer" value="true" class="mr-2" />
                                            Benar
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" v-model="question.correct_answer" value="false" class="mr-2" />
                                            Salah
                                        </label>
                                    </div>
                                </div>

                                <!-- Short Answer -->
                                <div v-else-if="question.type === 'short_answer'">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban Benar</label>
                                    <input
                                        v-model="question.correct_answer"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        placeholder="Masukkan jawaban yang benar"
                                    />
                                </div>

                                <!-- Essay (no correct answer) -->
                                <div v-else-if="question.type === 'essay'">
                                    <p class="text-sm text-gray-500 italic">Essay akan dinilai manual oleh guru</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Penjelasan (opsional)</label>
                                    <textarea
                                        v-model="question.explanation"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        placeholder="Penjelasan untuk feedback setelah jawaban..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3">
                    <Link :href="`/quizzes/${quiz.id}`" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing || form.questions.length === 0"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const props = defineProps({
    quiz: Object,
    courses: Array,
    kelasOptions: {
        type: Array,
        default: () => []
    }
});

// Format datetime for input
const formatDatetimeLocal = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().slice(0, 16);
};

const form = reactive({
    title: props.quiz.title || '',
    description: props.quiz.description || '',
    course_id: props.quiz.course_id || '',
    kelas: props.quiz.kelas || [],
    duration: props.quiz.duration || null,
    max_attempts: props.quiz.max_attempts || 1,
    passing_score: props.quiz.passing_score || 70,
    available_from: formatDatetimeLocal(props.quiz.available_from),
    available_until: formatDatetimeLocal(props.quiz.available_until),
    randomize_questions: props.quiz.randomize_questions || false,
    show_correct_answers: props.quiz.show_correct_answers ?? true,
    status: props.quiz.status || 'draft',
    questions: props.quiz.questions?.map(q => ({
        type: q.type,
        question_text: q.question_text,
        explanation: q.explanation || '',
        points: q.points,
        options: q.options || [{ text: '' }, { text: '' }],
        correct_answer: q.correct_answer
    })) || []
});

const processing = ref(false);

const addQuestion = () => {
    form.questions.push({
        type: 'multiple_choice',
        question_text: '',
        explanation: '',
        points: 1,
        options: [
            { text: '' },
            { text: '' }
        ],
        correct_answer: null
    });
};

const removeQuestion = (index) => {
    form.questions.splice(index, 1);
};

const onTypeChange = (index) => {
    const question = form.questions[index];
    
    if (question.type === 'multiple_choice' && !question.options) {
        question.options = [{ text: '' }, { text: '' }];
    } else if (question.type === 'true_false') {
        question.correct_answer = null;
        question.options = null;
    } else if (question.type === 'short_answer' || question.type === 'essay') {
        question.options = null;
        question.correct_answer = question.type === 'essay' ? null : '';
    }
};

const addOption = (questionIndex) => {
    form.questions[questionIndex].options.push({ text: '' });
};

const removeOption = (questionIndex, optionIndex) => {
    form.questions[questionIndex].options.splice(optionIndex, 1);
};

const submit = () => {
    processing.value = true;
    
    router.put(`/quizzes/${props.quiz.id}`, form, {
        preserveScroll: true,
        onSuccess: () => {
            // Redirect handled by controller
        },
        onError: () => {
            processing.value = false;
        },
        onFinish: () => {
            processing.value = false;
        }
    });
};
</script>
