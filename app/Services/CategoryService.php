<?php
namespace App\Services;

use App\Helpers\CommonHelper;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    private $crawlService;

    public function __construct(CrawlService $crawlService)
    {
        $this->crawlService = $crawlService;
    }

    public function getAll()
    {
        $categories = Category::all();

        return $categories;
    }

    public function getById(int $id)
    {
        $category = Category::findOrFail($id);

        return $category;
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->crawlService->getDataCrawler($request);

            //Storage to db
            $dataInput = [];
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $category) {
                    array_push($dataInput, [
                        'name' => $category,
                        'slug' => str_replace(' ', '-', strtolower(CommonHelper::strVnFilter($category)))
                    ]);
                }

                Category::upsert($dataInput, ['name'], ['slug']);
                DB::commit();

            }
            return $dataInput;
        } catch (\Exception $err) {
            DB::rollBack();
            throw new \Exception($err->getMessage());
        }
    }

    public function updateById(array $attrs, int $id)
    {
        $data = Category::findOrFail($id);
        $data->fill($attrs);

        if (!$data->isValidFor()) {
            throw new ValidationException($data->validator());
        }

        $data->save();

        return $data;
    }

    public function delete(int $id)
    {
        $data = Category::findOrFail($id);

        return (bool) $data->delete();
    }
}
