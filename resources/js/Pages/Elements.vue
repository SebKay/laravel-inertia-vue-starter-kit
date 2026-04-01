<template>
    <Head :title="title" />

    <div class="max-w-3xl">
        <div class="space-y-10 divide-y divide-neutral-300">
            <div class="space-y-6 pb-10">
                <h4 class="h4 heading">Forms</h4>

                <form>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="label" for="text"> Text </label>
                            <input
                                id="text"
                                class="input"
                                type="text"
                                required
                            />
                        </div>

                        <div class="form-col">
                            <label class="label" for="email"> Email </label>
                            <input
                                id="email"
                                class="input"
                                type="email"
                                required
                            />
                        </div>

                        <div class="form-col">
                            <label class="label" for="textarea">
                                Textarea
                            </label>
                            <textarea
                                id="textarea"
                                class="textarea"
                                required
                            ></textarea>
                        </div>

                        <div class="form-col">
                            <label class="inline-label" for="checkbox">
                                <input
                                    id="checkbox"
                                    class="checkbox"
                                    type="checkbox"
                                    required
                                />
                                Checkbox
                            </label>
                        </div>

                        <div class="form-col">
                            <label class="inline-label" for="radio">
                                <input
                                    id="radio"
                                    class="radio"
                                    type="radio"
                                    required
                                />
                                Radio
                            </label>
                        </div>

                        <div class="form-col">
                            <label for="toggle" class="toggle">
                                <input
                                    id="toggle"
                                    class="peer sr-only"
                                    type="checkbox"
                                />
                                <div class="peer"></div>
                                <span> Toggle </span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <h4 class="h4 heading">Content</h4>

                <div class="content">
                    <h1>Heading 1</h1>
                    <h2>Heading 2</h2>
                    <h3>Heading 3</h3>
                    <h4>Heading 4</h4>
                    <h5>Heading 5</h5>
                    <h6>Heading 6</h6>
                    <p>
                        Lorem ipsum dolor sit <strong>bold</strong> amet
                        <em>italic</em> consectetur adipisicing elit. Hic
                        praesentium consectetur eum
                        <a href="#">text link</a> laudantium optio temporibus.
                        Beatae, nulla natus aut accusamus sint culpa at itaque,
                        voluptates architecto laboriosam ipsum voluptatem
                        ducimus.
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Est ad totam distinctio consequatur quae a pariatur
                        asperiores obcaecati error officiis perspiciatis et
                        similique sit quas, rerum eius perferendis dolorum
                        culpa.
                    </p>
                    <ul>
                        <li>Unordered List Item 1</li>
                        <li>Unordered List Item 2</li>
                        <li>Unordered List Item 3</li>
                    </ul>
                    <ol>
                        <li>Ordered List Item 1</li>
                        <li>Ordered List Item 2</li>
                        <li>Ordered List Item 3</li>
                    </ol>
                    <blockquote>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing
                            elit. Hic praesentium consectetur eum laudantium
                            optio temporibus. Beatae, nulla natus aut accusamus
                            sint culpa at itaque, voluptates architecto
                            laboriosam ipsum voluptatem ducimus.
                        </p>
                    </blockquote>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Table Heading 1</th>
                                    <th>Table Heading 2</th>
                                    <th>Table Heading 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Table Data 1</td>
                                    <td>Table Data 2</td>
                                    <td>Table Data 3</td>
                                </tr>
                                <tr>
                                    <td>Table Data 1</td>
                                    <td>Table Data 2</td>
                                    <td>Table Data 3</td>
                                </tr>
                                <tr>
                                    <td>Table Data 1</td>
                                    <td>Table Data 2</td>
                                    <td>Table Data 3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { setLayoutProps } from "@inertiajs/vue3";

    const title = "Elements";

    setLayoutProps({
        heading: title,
    });
</script>
